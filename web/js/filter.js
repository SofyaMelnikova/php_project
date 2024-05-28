function filterByGenre(id) {
    console.log(id)
    postBlock.innerHTML = '';
    let request = new XMLHttpRequest();

    request.open('get', '/filter?genre=' + id, false);

    request.send();

    if (request.status === 500) {
        alert('AAAAAAAAAAA');
    } else if (request.status !== 200) {
        alert('wrong');
    } else {
        postBlock.innerHTML = (request.response);
    }
}