<?php

namespace App\Http\Requests\Progress;

use Illuminate\Foundation\Http\FormRequest;

class ProgressPhotoValidation extends FormRequest
{
  // use \App\Http\Requests\Response;

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return 
    [
      'title' => 'required',
      'date' => 'required',
      'image_type' => 'required',
      'selected_pose_type' => 'required',
      // 'file' => 'required',
    ];
  
  }

  public function messages()
  {
    return 
    [
      'image_type.required' =>'Image type(i.e Before,After,Progression) field is required.',
      'selected_pose_type.required' =>'Pose type field is required.',
      'file.required' =>'Image field is required',
    ];
  }

  public function withValidator($validator)
  { 
    $file = request()->file('file');
    $validator->after(function ($validator) use($file)
    {
      if($file == null && $this->drag_file != 'yes')
      {
        $validator->errors()->add('file', 'Image field is required');
      }
    });
    return;
  }
}
