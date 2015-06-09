@if($isContentMode && $isModePublic)

<section class="content_section panel panel-primary clearfix" data-section="{{ $section->id }}">
    
    <div class="panel-heading content_section__heading">

		{{ $section->title }}

		<div class="pull-right">
			<a href="#modal_{{ $section->id }}" data-toggle="modal" class="content_section__link"><i class="icon-plus"></i></a>
		</div>

    </div>

	<div class="modal fade" id="modal_{{ $section->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					{{ $form }}
				</div>
			</div>
		</div>
	</div>

    <div class="panel-body">

        @yield('before_blocks')
                
        @foreach($blocks as $block)
        {{ $block }}
        @endforeach
        
        @yield('after_blocks')
    
    </div>
    
</section>

@else

<section class="content_section ">	
    
    @foreach($blocks as $block)
	{{ $block }}
	@endforeach
    
</section>

@endif