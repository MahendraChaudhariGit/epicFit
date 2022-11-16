<?php
namespace App\Http\Controllers\SalesTools\Discount;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use App\SalesToolsDiscount;
use Auth;
use App\Http\Traits\HelperTrait;
use App\MemberShipTax;
use Input;
use App\SalesToolsInvoice;

class DiscountController extends Controller{
    use HelperTrait;

    private $cookieSlug = 'sales-tools-discount';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'list-discount'))
            abort(404);

        $alldiscount = array();
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);

        if($search)
            $alldiscount = SalesToolsDiscount::with('tax')->where('std_business_id',Session::get('businessId'))->where('std_name', 'like', "%$search%")->paginate($length);
        else
            $alldiscount = SalesToolsDiscount::where('std_business_id',Session::get('businessId'))->paginate($length);
       //$alldiscount = SalesToolsDiscount::with('tax')->where('std_business_id',Session::get('businessId'))->get();
       //dd($alldiscount);
       return view('SalesTools.Discount.index',compact('alldiscount'));
    }

    protected function calculateTaxes(){
        if(!Session::has('businessId'))
            abort(404);
        $taxes = MemberShipTax::where('mtax_business_id', Session::get('businessId'))->select('id','mtax_label')->get();
        $taxesdata = ['' => '-- Select --'];
        if($taxes->count()){
            foreach($taxes->sortBy('mtax_label') as $tax)
                $taxesdata[$tax->id] = $tax->mtax_label;
        }
        $taxesdata[0] = 'Not applicable';
        return $taxesdata;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
       if(!Session::has('businessId') || !isUserEligible(['Admin'], 'add-discount'))
            abort(404); 
        $globleTaxId = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->pluck('sti_override')->first();  
        $taxesdata = $this->calculateTaxes();
        return view('SalesTools.Discount.create',compact('taxesdata','globleTaxId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
       if(!Session::has('businessId') || !isUserEligible(['Admin'], 'add-discount'))
            abort(404);
        $data = new SalesToolsDiscount;
        $data->std_business_id = Session::get('businessId');
        $data->std_name = $request->discountName;
        $data->std_tax = $request->taxId;
        $data->std_type = $request->discountType;
        if($data->std_type == "Percentage" || $data->std_type == "Fixed amount" )
            $data->std_value = $request->discountValue;
        else
            $data->std_value = null;

        if($data->save()){
            Session::put('ifBussHasSalesToolsDiscounts', true);
            $result = array("status"=>"success","discountid"=>$data->std_id);
        }
        else
            $result = array("status"=>"fail");
      
      echo json_encode($result);  
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        if(!Session::has('businessId'))
            abort(404);
        //$alldiscount = SalesToolsDiscount::where('std_business_id',Session::get('businessId'))->get();
       //return view('SalesTools.Discount.index',compact('alldiscount'));
        //return view('SalesTools.Discount.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
       if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-discount'))
            abort(404);
        $discount = SalesToolsDiscount::where('std_business_id',Session::get('businessId'))->find($id);
        $taxesdata = $this->calculateTaxes();
        return view('SalesTools.Discount.edit',compact('discount','taxesdata'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $isError = false;

        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-discount')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        
        if(!$isError){
            $data = SalesToolsDiscount::where('std_business_id',Session::get('businessId'))->find($id);
            $data->std_business_id = Session::get('businessId');
            $data->std_name = $request->discountName;
            $data->std_tax = $request->taxId;
            $data->std_type = $request->discountType;
            if($data->std_type == "Percentage" || $data->std_type == "Fixed amount" )
                $data->std_value = $request->discountValue;
            else
                $data->std_value = null;

            if($data->update())
                $result = array("status"=>"success");
            else
                $result = array("status"=>"fail");

            echo json_encode($result);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'delete-discount'))
            abort(404);
        $discount = SalesToolsDiscount::findOrFailDiscount($id);
        $discount->delete();

        if(!SalesToolsDiscount::OfBusiness()->exists())
                Session::forget('ifBussHasSalesToolsDiscounts');
            
            return redirect()->back()->with('message', 'success|Data has been deleted successfully.');
            //route('salestools.discount.list')
        //dd("destroy");
    }
}
