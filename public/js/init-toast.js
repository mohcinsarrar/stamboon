
function show_toast(type,title,msg){
    const toastAnimationExample = document.querySelector('.toast-ex')
    toastAnimationExample.classList.add("animate__tada");
    toastAnimationExample.querySelector('.ti').classList.add("text-"+type);
    toastAnimationExample.querySelector('.title').innerHTML = title;
    toastAnimationExample.querySelector('.toast-body').innerHTML = msg;

    toastAnimation = new bootstrap.Toast(toastAnimationExample);
    toastAnimation.show();
}
