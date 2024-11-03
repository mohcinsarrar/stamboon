$('#offcanvasAddProduct #add-product-features')
    .select2({
        tags: true
    });

$('#offcanvasUpdateProduct #update-product-features')
    .select2({
        tags: true
    });

function updateProduct($product){

        var canvas = document.getElementById('offcanvasUpdateProduct')
        var bsOffcanvas = new bootstrap.Offcanvas(canvas)
        // reset forms
        document.getElementById('updateProductForm').reset()
        document.querySelector('#updateProductForm #update-product-image-preview').src = ''

        // fill out the form
        document.getElementById('updateProductForm').action = "/admin/webshop/products/" + $product.id
        document.querySelector('#updateProductForm #update-product-name').value = $product.name;
        document.querySelector('#updateProductForm #update-product-price').value = $product.price;
        if($product.image != null){
            document.querySelector('#updateProductForm #update-product-image-preview').src = '/storage/'+$product.image;
        }
        else{

        }
        
        document.querySelector('#updateProductForm #update-product-description').innerHTML = $product.description;
        document.querySelector('#updateProductForm #update-product-duration').value = $product.duration;
        document.querySelector('#updateProductForm #update-product-print_number').value = $product.print_number;
        document.querySelector('#updateProductForm #update-product-chart_type1').checked = $product.fanchart;
        document.querySelector('#updateProductForm #update-product-chart_type2').checked = $product.pedigree;
        
        document.querySelector('#updateProductForm #update-product-fanchart_max_generation').value = $product.fanchart_max_generation;
        document.querySelector('#updateProductForm #update-product-pedigree_max_generation').value = $product.pedigree_max_generation;

        document.querySelector('#updateProductForm #update-product-max_nodes').value = $product.max_nodes;

        document.querySelector('#updateProductForm #update-product-fanchart_print_type1').checked = $product.fanchart_output_png;
        document.querySelector('#updateProductForm #update-product-fanchart_print_type2').checked = $product.fanchart_output_pdf;

        document.querySelector('#updateProductForm #update-product-pedigree_print_type1').checked = $product.pedigree_output_png;
        document.querySelector('#updateProductForm #update-product-pedigree_print_type2').checked = $product.pedigree_output_pdf;

        document.querySelector('#updateProductForm #update-product-fanchart_max_output_png').value = $product.fanchart_max_output_png;
        document.querySelector('#updateProductForm #update-product-fanchart_max_output_pdf').value = $product.fanchart_max_output_pdf;

        document.querySelector('#updateProductForm #update-product-pedigree_max_output_png').value = $product.pedigree_max_output_png;
        document.querySelector('#updateProductForm #update-product-pedigree_max_output_pdf').value = $product.pedigree_max_output_pdf;


        bsOffcanvas.show()
}