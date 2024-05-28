const cardPost = document.getElementsByClassName("card-wrapper");

for (let i = 0; i < cardPost.length; i++) {
    let card = cardPost.item(i);
    console.log(card)

    const hrefId = card.querySelector("#postId");
    const href = hrefId.getAttribute("href");
    const postId = href.slice(9);

    const btn = card.querySelector("#delete");
    console.log(btn)

    btn.addEventListener('click', () => {
        console.log(postId);

        fetch(`/profile/delete?postId=${postId}`)
            .then(response => {
                if (response.status === 500) alert('the programmer is a teapot ;)');
                else if (response.status !== 200) alert("some problems");
            }).then(result => card.remove());
    })
}