

document.addEventListener("DOMContentLoaded", function(event) {
    let buttons = document.querySelectorAll(".social_share");
    for(let i = 0; i < buttons.length; i++){
        buttons[i].addEventListener('click', function() {
            return JSShare.go(this);
        }, false);
    }
});



const button = document.querySelector('.form button.btn');
button.addEventListener('click', addComment)

async function addComment() {

    const form = document.querySelector('form.form');
    const _token = document.querySelector('meta[name="_token"]').content;
    
    const formData = new FormData(form)
    formData.append('_token',_token)
    const data = formValidate(formData)
    console.log(data)
    if (data && Object.keys(data).length >= 3){
        document.querySelector('.overwrite').classList.add('active')
        await  axios.post('/', data)
            .then(response => {
                const comment = getHtmlComment( response.data )
                setCommentDocument(comment )
            })
            .catch(error => {
                if ('response' in error){
                    const data = error.response.data
                    for (let key in data){
                        const errorElement = document.querySelector(`[name=${key}]`).nextElementSibling
                        setErrorText(errorElement, data[key])
                    }
                }
            }).finally(() => {
                document.querySelector('.overwrite').classList.remove('active')
            })
    }


}

function setCommentDocument(comment ) {
     document.querySelector('.comments').insertAdjacentHTML('beforeend', comment)
     document.querySelector('form.form').reset()
}



function formValidate( formData ){
    const data = {}
    let error = false
    formData.forEach((el,key) => {
        const errorElement =  document.querySelector(`[name=${key}]`).nextElementSibling
        errorElement.classList.remove('invalid')
        if ( el.trim().length < 3 ){
            setErrorText(errorElement, `Поля ${key} обезателно для зополнение мнимум 3 символа`)
            error = true
        }
        if (key === 'email'){
            const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            if (!el.match(validRegex)){
                setErrorText(errorElement, `Поля ${key} заполнено не провилно`)
                error = true
            }
        }

        data[key] = el
    })

    return error ? error : data
}


function setErrorText(errorElement, text ){
    errorElement.textContent = text
    errorElement.classList.add('invalid')
}


function getHtmlComment( comment ) {
     return `<div class="cart">
                <div class="name"><span class="label">Имя: </span> <span class="value"> ${comment.name} </span></div>
                <div class="email"><span class="label">Почта: </span> <span class="value">${comment.email}</span></div>
                <div class="comment"><span class="label">Коментария: </span> <span class="value">${comment.comment}</span></div>
                <div class="date"><span class="label">Дата: </span> <span class="value">${comment.created_at}</span> </div>
            </div>`
}