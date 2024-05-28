const submitBtn = document.getElementById('submit');

submitBtn.addEventListener('click', (e) => {
    let password = document.getElementById('password').value;
    let passwordConfirm = document.getElementById('passwordConfirm').value;

    console.log(password);
    console.log(passwordConfirm);

    if (password !== passwordConfirm){
        alert("Password do not matches!")
        e.preventDefault();
    }
});