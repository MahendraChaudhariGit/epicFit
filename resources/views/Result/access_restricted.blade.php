@extends('Result.masters.app')

@section('page-title')
@stop
<style type="text/css">
	body {
  padding: 0;
  margin: 0;
  background: repeating-linear-gradient(45deg, darkred, darkred 2px, #330000 3px, #330000 8px);
  -webkit-user-select: none;         
  -moz-user-select: none; 
  -ms-user-select: none;
}
#spam {
  background-color: #ff44012e;
  border-radius: 5px;
  margin: auto;
  width: 85%;
  cursor: not-allowed;
}
h1.restricteHeading {
  padding-top: 30px;
  font-size: 60px;
  font-family: Arial;
  text-align: center;
  border-bottom: 1px solid #333;
  border-bottom-style: dashed;
  padding-bottom: 20px;
}
span.prevUrl {
  padding-top: 5px;
  font-size: 20px;
  font-family: Arial;
  border-bottom: 1px solid #333;
  border-bottom-style: dashed;
  padding-bottom: 5px;
}
spam {
   color: red;
   text-decoration: none;
   cursor: not-allowed;
}
p {
  text-align: center;
  font-family: Arial;
  font-weight: bold;
  padding-bottom: 3%;
}
small {
  font-family: Arial;
  text-align: center;
  word-spacing: -1px;
  font-size: 10px;
  font-weight: normal;
  color: gray;
}
</style>

@section('content')
  <div id="spam">
    <h1 class="restricteHeading"><spam>X</spam> Access Restricted</h1>
    <p>You are not allowed to access this page.<br/><br/>
    </p>
  </div>
@endsection
