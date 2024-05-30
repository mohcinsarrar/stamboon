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

        document.querySelector('#updateProductForm #update-product-features').innerHTML = ""
        // fill out the form
        document.getElementById('updateProductForm').action = "/admin/webshop/products/" + $product.id
        document.querySelector('#updateProductForm #update-product-name').value = $product.name;
        document.querySelector('#updateProductForm #update-product-amount').value = $product.amount;
        document.querySelector('#updateProductForm #update-product-description').innerHTML = $product.description;
        console.log($product.features);

        $product.features.forEach((value, index, array) => {
            options = "<option selected>"+value+"</option>";
            document.querySelector('#updateProductForm #update-product-features').innerHTML += options;
        });

        bsOffcanvas.show()
}