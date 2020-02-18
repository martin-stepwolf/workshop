@extends('main')
@section('title', '| Home')
@section('content')
<div class="row well">
  <div class="col-xs-12 col-sm-12 col-md-12 main-title">
    <h1 class="text-center text-uppercase">Gestión documentos e información taller</h1>
  </div>
</div> 
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
      <hr>
  </div>
</div>       
<div class="row main-index">
  <div class="col-xs-12 col-sm-6 col-md-3 text-center">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">
        <h3 class="text-uppercase">Calidad</h3>
      </div>
      <div class="col-md-12 main-index-image">
        <a href="qdocs"><img src="{{asset('img/list.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>  
  </div>
  <div class="col-xs-12 col-sm-6 col-md-3 text-center main-index-option">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">
        <h3 class="text-uppercase">Peritaje</h3>
      </div>
      <div class="col-md-12 main-index-image"> 
        <a href="edocs"><img src="{{asset('img/car-inspection.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-3 text-center main-index-option">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">  
        <h3 class="text-uppercase">Inspección</h3>
      </div> 
      <div class="col-md-12 main-index-image">  
        <a href="idocs"><img src="{{asset('img/car-repair-check.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-3 text-center main-index-option">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">  
        <h3 class="text-uppercase">Colisión</h3>
      </div> 
      <div class="col-md-12 main-index-image">  
        <a href="cdocs"><img src="{{asset('img/car-painting.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>
  </div>
</div>
<div class="row main-index">
  <div class="col-xs-12 col-sm-6 col-md-3 text-center main-index-option">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">  
        <h3 class="text-uppercase">Fotos OR</span></h3>
      </div> 
      <div class="col-md-12 main-index-image">
        <a href="otdocs"><img src="{{asset('img/photo-camera.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-3 text-center main-index-option">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">
        <h3 class="text-uppercase">Precios OM</h3>
      </div> 
      <div class="col-md-12 main-index-image">
        <a href="prices"><img src="{{asset('img/label.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-3 text-center main-index-option">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">
        <h3 class="text-uppercase">Cotizaciones</h3>
      </div> 
      <div class="col-md-12 main-index-image">
        <a href="quote"><img src="{{asset('img/price.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-3 text-center main-index-option">
    <div class="col-xs-12 col-sm-12 col-md-12 well">
      <div class="col-md-12 main-index-title">
        <h3 class="text-uppercase">Correo</h3>
      </div> 
      <div class="col-md-12 main-index-image">
        <a href="mail"><img src="{{asset('img/email.png')}}" class="img-responsive center-block"></a>
      </div>
    </div>
  </div>
</div>
@endsection
  