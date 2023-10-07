<div class="col-md-3 sidebar">
    <div class="sidebar-container cscroller">
        @foreach ($tops as $top)
            @include('themes::themetocanime.inc.right_bar.' . $top['template'])
        @endforeach
    </div>
</div>
