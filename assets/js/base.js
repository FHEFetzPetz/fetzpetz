document.addEventListener('DOMContentLoaded', function() {
    const scriptData = document.getElementById('script-data');
    var data = {};
    if (scriptData) data = JSON.parse(scriptData.getAttribute('data-value'));

    scrollTopButton = document.getElementById("scroll-top");

    function toggleLikeProduct(id, item) {
        const active = item.classList.contains('active')
        if (data.authenticated) {
            var request = new XMLHttpRequest();
            const url = active ? (data.wishlistRemove + id) : (data.wishlistAdd + id);
            request.open("GET", url);
            request.addEventListener('load', function () {
                if (request.status === 200) {
                    item.classList.toggle('active');

                    if (active) pushNotification('Removed from Wishlist', 'Item has been removed from the Wishlist');
                    else pushNotification('Added to Wishlist', 'Item has been added to the Wishlist');
                } else
                    pushNotification('Operation failed', 'Due to unexpected Error your request could not be processed');
            });

            request.send();
        } else {
            window.location = active ? (data.wishlistRemoveRedirect + id) : (data.wishlistAddRedirect + id);
        }
    }

    this.querySelectorAll('.product-card .like-button').forEach(function (item) {
        item.addEventListener('click', function (event) {
            event.preventDefault();

            const id = item.closest('.product-card').getAttribute('data-id');
            toggleLikeProduct(id, item);
        })
    });

    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            scrollTopButton.classList.add("reveal");
        } else {
            scrollTopButton.classList.remove("reveal");
        }
    }

    scrollTopButton.addEventListener("click", function () {
        document.body.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        })
    });

    if (data.navigation) {
        document.querySelector("#navigation .menu-toggle").addEventListener("click", function () {
            document.getElementById("navigation").classList.add("reveal-menu");
        });

        document.querySelector("#navigation .menu .close").addEventListener("click", function () {
            document.getElementById("navigation").classList.remove("reveal-menu");
        });

        document.querySelector("#navigation .menu-overlay").addEventListener("click", function () {
            document.getElementById("navigation").classList.remove("reveal-menu");
        });

        document.querySelector("#navigation .menu").addEventListener("click", function (e) {
            e.stopPropagation();
        });

        if(!data.slim) {
            document.querySelector("#navigation .search-box .search-button").addEventListener("click", function (e) {
                if (window.innerWidth < 900) {
                    const navigation = document.getElementById("navigation");
                    if (!navigation.classList.contains('search')) {
                        e.preventDefault();
                        document.getElementById("navigation").classList.add("search");
                    }
                }
            });
        }
    }

    document.querySelectorAll('#notifications .notification .close').forEach(function(item) {
        item.addEventListener('click', function() {
            this.closest('.notification').remove();
        });
    });

    function pushNotification(title, message, type) {
        const notification = document.createElement('div');
        notification.classList.add('notification');
        notification.innerHTML = "<div class='title'>" + title + "</div><span>" + message + "</span><div class='close'><i class='icon times'></i></div>";
        notification.setAttribute('data-type', type);

        notification.addEventListener('click', function() {
            this.closest('.notification').remove();
        });

        window.setTimeout(function() {
            notification.classList.add('fade');
            window.setTimeout(function() {
                notification.remove();
            }, 300);
        }, 1000 * 6);

        document.getElementById('notifications').appendChild(notification);
    }

    window.setTimeout(function() {
        document.querySelectorAll('#notifications .notification').forEach(function(item) {
            item.classList.add('fade');
            window.setTimeout(function() {
                item.remove();
            }, 300);
        });
    }, 1000 * 6);
});

document.querySelector('#sidebar .item.chin').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('opened');
});

function awaitConfirmation(question, callback) {
    const result = confirm(question);
    if(result) callback();
}

document.querySelectorAll('.delete-confirmation').forEach(function(item) {
    item.addEventListener('click', function(e) {
        e.preventDefault();

        awaitConfirmation(item.getAttribute('data-question'), function() {
            document.location = item.getAttribute('href');
        });
    });
});