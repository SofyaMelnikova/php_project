const postBlock = document.getElementById('main-post-block');

function searchTitle(title) {
    postBlock.innerHTML = '';
    let request = new XMLHttpRequest();

    request.open('get', '/search?title=' + title, false);

    request.send();

    if (request.status === 500) {
        alert('AAAAAAAAAAA');
    } else if (request.status !== 200) {
        alert('wrong');
    } else {
        postBlock.innerHTML = (request.response);
    }
}