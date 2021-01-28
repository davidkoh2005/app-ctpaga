<div class="col-12 mx-auto">
    <ul class="nav flex-column" >
        <li class="nav-item">
            <a class="nav-link listCategories category-current" href="javascript:;">
                <div class="category-image">{{strtoupper(substr('Todos',0,1))}}</div>
                <input type="hidden" id="category-id" value="">
                <label class="category-name">Todos</label>
            </a>
        </li>

        @foreach ($categories as $category)
            <li class="nav-item">
                <a class="nav-link listCategories" href="javascript:;">
                    <div class="category-image">{{strtoupper(substr($category->name,0,1))}}</div>
                    <input type="hidden" id="category-id" value="{{$category->id}}">
                    <label class="category-name">{{$category->name}}</label>
                </a>
            </li>
        @endforeach 
    </ul>
</div>

<script>
$(function(){
    $('.listCategories').click(function(){
        $('.listCategories').removeClass("category-current");

        $( ".loader" ).fadeIn(150, function() {
            $( ".loader" ).fadeIn("slow"); 
        }); 

        categorySelect = $(this).find('#category-id').val();
        $(this).addClass("category-current");
        showProductsServices(categorySelect);
    });
});

    
</script>