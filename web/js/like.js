const likeBtn = document.getElementById('like');

likeBtn.addEventListener('click', () => {

    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');

    console.log(likeBtn.getAttribute('class'));

    if (likeBtn.getAttribute('class') === 'true') {
        fetch(`/unlike?postId=${postId}`, {
            method: 'GET'
        })
            .then(response => {
                if (response.status === 500) alert('the programmer is a teapot ;)');
                else if (response.status !== 200) alert("some problems");
            })
            .then(() => {
                likeBtn.setAttribute('class', 'false')
                let svg = document.querySelector('.cls-1');
                svg.setAttribute('fill', '#c80000')
            })
    } else {
        fetch(`/like?postId=${postId}`, {
            method: 'GET'
        })
            .then(response => {
                if (response.status === 500) alert('the programmer is a teapot ;)');
                else if (response.status !== 200) alert("some problems");
            })
            .then(() => {
                likeBtn.setAttribute('class', 'true')
                let svg = document.querySelector('.cls-1');
                svg.setAttribute('fill', '#fff6f6')
            })
    }
});