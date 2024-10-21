document.getElementById('burger-icon').addEventListener('click', function() {
    this.classList.toggle('open');
    document.getElementById('menu').classList.toggle('active');
    document.getElementById('blur-overlay').classList.toggle('active');
});

document.getElementById('blur-overlay').addEventListener('click', function() {
    document.getElementById('menu').classList.remove('active');
    document.getElementById('blur-overlay').classList.remove('active');
    document.getElementById('burger-icon').classList.remove('open');
});

function myFunction(dropdownId) {
    var dropdownContent = document.getElementById(dropdownId);
    dropdownContent.classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// Слайдер
let currentIndex = 0;
const slides = document.querySelectorAll('.slaid');
const nextButton = document.querySelector('#nextSlide');
const prevButton = document.querySelector('#prevSlide');

function showSlide(index) {
    slides.forEach(item => {
        item.classList.remove('active');
    });
    slides[index].classList.add('active');
}

function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
}

function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
}

if (nextButton && prevButton) { // Проверка на существование элементов
    nextButton.addEventListener('click', nextSlide);
    prevButton.addEventListener('click', prevSlide);
}

// Модальное окно
let openButton = document.querySelector('#open-modal');
let modal = document.querySelector('#modal');
let closeButton = document.querySelector('#close-modal');

if (openButton && closeButton) { // Проверка на существование элементов
    openButton.addEventListener('click', toggleModal);
    closeButton.addEventListener('click', toggleModal);
}

function toggleModal() {
    modal.classList.toggle('active');
}

window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.classList.remove('active');
    }
});

let blocks = document.querySelectorAll('.block')

blocks.forEach(block => {
    let button = block.querySelector('.buttonOpen')
    let text = block.querySelector('.text')

    button.addEventListener('click', () => {
        if (text.style.display === 'block') {
            text.style.display = 'none'
        } else {
            text.style.display = 'block'
        }

    })
})


// Кнопки администартора 
let zacazs = document.getElementById('zacazs')
let tovars = document.getElementById('tovars')
let zacazContent = document.getElementById('zacaz')
let work_tovar = document.getElementById('work_tovar')

if (zacazs && tovars) { // Проверка на существование элементов
    tovars.addEventListener('click', () => {
        work_tovar.style.display = 'block'
        zacazContent.style.display = 'none'
        tovars.classList = 'bot'
        zacazs.classList = 'b'
    })

    zacazs.addEventListener('click', () => {
        work_tovar.style.display = 'none'
        zacazContent.style.display = 'block'
        tovars.classList = 'b'
        zacazs.classList = 'bot'
    })
}

