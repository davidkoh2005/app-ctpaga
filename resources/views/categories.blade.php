<div class="col">
    <div class="card listCategories category-current">
        <div class="card-body">
            <div class="category-circle">{{strtoupper(substr('Todos',0,1))}}</div>
            <div class="category-name">Todos</div>
            <input type="hidden" id="category-id" value="">
        </div>
    </div>
</div>

@foreach ($categories as $category)
<div class="col">
    <div class="card listCategories">
        <div class="card-body">
            <div class="category-circle">{{strtoupper(substr($category->name,0,1))}}</div>
            <div class="category-name" id="category-name">{{$category->name}}</div>
            <input type="hidden" id="category-id" value="{{$category->id}}">
        </div>
    </div>
</div>
@endforeach 

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