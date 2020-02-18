@extends('layouts.app')

@section('content')
<div class="container">
    
        
           <search :trending="{{ json_encode($trending) }}" :query="{{ json_encode($query) }}"></search> 
       
                 
            
                          
          
       
    
</div>
@endsection