
@if($isContentMode && $isModePublic)
    
<section class="content_block panel panel-primary clearfix" data-url-update="{{ URL::route('admin.content.update', array($content->id)) }}"">

	@if(!$content->layout_id && $content->block)
    <div class="panel-heading content_block__heading">

        @if($content->block)
        {{ $content->block->title }}
        @else
        {{ $content->controller }}
        @endif

		<div class="pull-right">
			@if($hasConfigForm)
			<a href="{{ URL::route('admin.content.config.edit', $content->id) }}?mode=view" class="content_block__link btn btn-link btn-default"><i class="icon-wrench"></i></a>
			@endif
			<div class="pull-right">
			{{ Form::open(array('route' => array('admin.content.destroy', $content->id), 'method' => 'delete', 'class' => 'form-inline')) }}
			{{ Form::button('<i class="icon-remove"></i>', array('type' => 'submit', 'class' => 'content_block__link btn btn-link btn-default')) }}
			{{ Form::close() }}
			</div>
		</div>

    </div>
	@endif

    <div class="panel-body">
        
		<div class="content_block__inner">{{ $response }}</div>
        
    </div>
        
</section>

@else
   
<section class="content_block">
    
	{{ $response }}
    
</section>

@endif