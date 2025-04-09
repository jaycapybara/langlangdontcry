$(document).ready(function () {
    let menuItems = [];
    let categories = ['全部'];

    function fetchMenu() {
        $.ajax({
            url: 'menu.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                menuItems = data;
                categories = ['全部', ...new Set(data.map(item => item.menuLayer))];
                renderCategories();
                renderMenu();
            },
            error: function (error) {
                console.error('載入菜單失敗:', error);
            }
        });
    }

    function renderCategories() {
        let categoryHtml = categories.map(category => `<button class="category-btn" data-category="${category}">${category}</button>`).join('');
        $('#category-container').html(categoryHtml);
    }

    function renderMenu(category = '全部') {
        let filteredMenu = category === '全部' ? menuItems : menuItems.filter(item => item.menuLayer === category);
        let menuHtml = `
            <div class="menu-slider">
                <div class="slider-wrapper">
                    ${filteredMenu.map(item => `
                        <div class="slide">
                            <img src="images/menu/${item.image_path}" alt="${item.Pname}">
                            <h3>${item.Pname}</h3>
                            <p>${item.Remark}</p>
                            <span>$${item.Price}</span>
                        </div>
                    `).join('')}
                </div>
                <button class="prev-slide">&#10094;</button>
                <button class="next-slide">&#10095;</button>
            </div>
        `;
        $('#menu-container').html(menuHtml);
        initSlider();
    }

    function initSlider() {
        let currentIndex = 0;
        const slides = $('.slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.hide();
            slides.eq(index).fadeIn();
        }

        $('.prev-slide').click(function () {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalSlides - 1;
            showSlide(currentIndex);
        });

        $('.next-slide').click(function () {
            currentIndex = (currentIndex < totalSlides - 1) ? currentIndex + 1 : 0;
            showSlide(currentIndex);
        });

        showSlide(currentIndex);
    }

    $(document).on('click', '.category-btn', function () {
        let selectedCategory = $(this).data('category');
        renderMenu(selectedCategory);
    });

    fetchMenu();
});



// $(document).ready(function () {
//     let menuItems = [];
//     let categories = ['全部'];

//     function fetchMenu() {
//         $.ajax({
//             url: 'menu.php',
//             method: 'GET',
//             dataType: 'json',
//             success: function (data) {
//                 menuItems = data;
//                 categories = ['全部', ...new Set(data.map(item => item.menuLayer))];
//                 renderCategories();
//                 renderMenu();
//             },
//             error: function (error) {
//                 console.error('載入菜單失敗:', error);
//             }
//         });
//     }

//     function renderCategories() {
//         let categoryHtml = categories.map(category => `<button class="category-btn" data-category="${category}">${category}</button>`).join('');
//         $('#category-container').html(categoryHtml);
//     }

//     function renderMenu(category = '全部') {
//         let filteredMenu = category === '全部' ? menuItems : menuItems.filter(item => item.menuLayer === category);
//         let menuHtml = filteredMenu.map(item => `
//             <div class="menu-item">
//                 <img src="images/menu/${item.image_path}" alt="${item.Pname}">
//                 <h3>${item.Pname}</h3>
//                 <p>${item.Remark}</p>
//                    <p>${item.Pstatus}</p>
//                 <span>$${item.Price}</span>
//             </div>
//         `).join('');
//         $('#menu-container').html(menuHtml);
//     }

//     $(document).on('click', '.category-btn', function () {
//         let selectedCategory = $(this).data('category');
//         renderMenu(selectedCategory);
//     });

//     fetchMenu();
// });
