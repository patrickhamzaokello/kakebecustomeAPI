const apiKey =
    "pk.eyJ1IjoicGthc2VtZXIiLCJhIjoiY2wxYzNwMnRrMDN2czNkbzBnd2NtM3B5ZSJ9.anQZtZnER9oJ2rodYqx-XQ";

const mymap = L.map("map").setView([2.24003890, 32.89516390], 14);

L.tileLayer(
    "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}",
    {
        maxZoom: 18,
        id: "mapbox/streets-v11",
        tileSize: 512,
        zoomOffset: -1,
        accessToken: apiKey,
    }
).addTo(mymap);

//Add circle
const circle = L.circle([2.24003890, 32.89516390], {
    radius: 100,
    color: "green",
}).addTo(mymap);

const url = "https://xyzobide.kakebeshop.com/KakebeAPI/Requests/Address/allAddresses.php?page=1";
var data = [];
var layerGroup = L.layerGroup().addTo(mymap);

async function getInfrastructure() {
    const response = await fetch(url);
    data = await response.json();

    const {page, user_address, total_pages, total_results} = data;

    user_address.forEach((element) => {
        const {
            id,
            user_id,
            username,
            email,
            address,
            country,
            city,
            phone,
            set_default,
            created_at,
            updated_at,
            longitude,
            latitude,
            postal_code,
            shipping_cost
        } = element;

        // create markers
        const marker = L.marker([latitude, longitude]).addTo(layerGroup);

        //Add popup message
        let template = `
            
            <div class="infras_card" style="border-radius: 20px;background-color:#ffffff ; padding: 1em; color: #282828; font-size: 15px;">
            <h1 style="color: #171717; font-size: 22px;">${username}</h1>
            <p style="margin: 0;color: #4b4b4b;">${address}${city}</p>
            <p style="margin: 0;color:#4f4f4f ;margin-top: 1em;"><span style="color: #CDEDCB;"></span>${email}</p>
            <p style="margin: 0;"><span style="color: #444444;">Contact</span>${phone}</p>
            <p style="margin: 0;"><span style="color: #4b4b4b;">Date</span>${created_at}</p>
            <p style=" margin: 0; color: #570c37; margin-top: 1em; font-size: 12px;">This location may change based on the Order</p>
          </div>
            `;
        marker.bindPopup(template);
    });
}

//filter js

const checkboxes = document.querySelectorAll("input[type='checkbox']");
const cardContainer = document.getElementById("wrapper");

var checkboxValues = [];

getInfrastructure();

checkboxes.forEach((box) => {
    // box.checked = false;
    box.addEventListener("change", () => filterCards());
});

function grabCheckboxValues() {
    var checkboxValues = [];
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) checkboxValues.push(checkbox.value);
    });
    console.log(checkboxValues);
    return checkboxValues;
}

function filterCards() {
    // mymap.eachLayer((layer) => {
    //   layer.remove();
    // });
    // remove all the markers in one go
    layerGroup.clearLayers();

    checkboxValues = grabCheckboxValues();
    const {total_results, infrastructure} = data;
    infrastructure.forEach((element) => {
        const {id, aim, description, longitude, latitude, type} = element;

        let isMatch = checkboxValues.includes(type);
        let isAll = checkboxValues.includes("all");
        if (isMatch) {
            // create markers
            const marker = L.marker([latitude, longitude]).addTo(layerGroup);

            //Add popup message
            let template = `
            <div class="infras_card" style="border-radius: 20px;background-color:#ffffff ; padding: 1em; color: #282828; font-size: 15px;">
            <h1 style="color: #171717; font-size: 22px;">${username}</h1>
            <p style="margin: 0;color: #4b4b4b;">${address}${city}</p>
            <p style="margin: 0;color:#4f4f4f ;margin-top: 1em;"><span style="color: #CDEDCB;"></span>${email}</p>
            <p style="margin: 0;"><span style="color: #444444;">Contact</span>${phone}</p>
            <p style="margin: 0;"><span style="color: #4b4b4b;">Date</span>${created_at}</p>
            <p style=" margin: 0; color: #570c37; margin-top: 1em; font-size: 12px;">This location may change based on the Order</p>
          </div>
            `;
            marker.bindPopup(template);
        } else if (isAll) {
            // create markers
            const marker = L.marker([latitude, longitude]).addTo(layerGroup);

            //Add popup message
            let template = `

            <div class="infras_card" style="border-radius: 20px;background-color:#ffffff ; padding: 1em; color: #282828; font-size: 15px;">
            <h1 style="color: #171717; font-size: 22px;">${username}</h1>
            <p style="margin: 0;color: #4b4b4b;">${address}${city}</p>
            <p style="margin: 0;color:#4f4f4f ;margin-top: 1em;"><span style="color: #CDEDCB;"></span>${email}</p>
            <p style="margin: 0;"><span style="color: #444444;">Contact</span>${phone}</p>
            <p style="margin: 0;"><span style="color: #4b4b4b;">Date</span>${created_at}</p>
            <p style=" margin: 0; color: #570c37; margin-top: 1em; font-size: 12px;">This location may change based on the Order</p>
          </div>
            `;
            marker.bindPopup(template);
        }
    });
}

const body = document.querySelector("body"),
    sidebar = body.querySelector("nav"),
    searchBtn = body.querySelector(".search-box"),
    modeSwitch = body.querySelector(".toggle-switch"),
    modeText = body.querySelector(".mode-text");

searchBtn.addEventListener("click", () => {
    sidebar.classList.remove("close");
});

modeSwitch.addEventListener("click", () => {
    body.classList.toggle("dark");

    if (body.classList.contains("dark")) {
        modeText.innerText = "Light mode";
    } else {
        modeText.innerText = "Dark mode";
    }
});

var example_array = {
    ValueA: "Gulu",
};

var select = document.getElementById("example-select");
for (index in example_array) {
    select.options[select.options.length] = new Option(
        example_array[index],
        index
    );
}

class ResponsiveNav {
    constructor(options) {
        this.state = options.state;
        this.nav = options.nav;
        this.checkpoint = options.checkpoint;
        this.navContent = options.nav + " *";
        this.tuggleBtn = options.tuggleBtn;
        this.tuggleBtnContent = options.tuggleBtn + " *";
        this.navLink = options.navLink;
        this.navLinkContent = options.navLink + " *";

        this.$nav = $(this.nav);

        this._eddClickEvents();
        this._addResizeEvent();
    }

    _addResizeEvent() {
        window.addEventListener(
            "resize",
            () => {
                if (window.innerWidth > this.checkpoint) this._cleare();
            },
            true
        );
    }

    _eddClickEvents() {
        document.addEventListener(
            "click",
            (e) => {
                if (window.innerWidth > this.checkpoint) return;

                if ($(e.target).is(`${this.tuggleBtn}, ${this.tuggleBtnContent}`))
                    this._toggleNav(true);
                else if ($(e.target).is(`${this.navLink}, ${this.navLinkContent}`))
                    this._toggleNav(false);
                else if ($(e.target).is(`${this.nav}, ${this.navContent}`)) return;
                else this._toggleNav(false);
            },
            true
        );
    }

    _cleare() {
        this._toggleNav(false);
    }

    _toggleNav(ifNavClosed) {
        if (ifNavClosed || this.$nav.hasClass(this.state))
            this.$nav.toggleClass(this.state);
    }
}

class ChangeNavIfWindowScroll {
    constructor(options) {
        this.options = options;
        this.nav = document.querySelector(this.options.nav);
        this.state = this.options.state;
        this.heightActivateState = this.options.heightActivateState;

        this._startToggleState();
    }

    _startToggleState() {
        $(window).on("scroll", () => {
            let scrolled = window.pageYOffset || document.documentElement.scrollTop,
                classList = this.nav.classList;

            if (scrolled > this.heightActivateState) {
                if (!classList.contains(this.state)) classList.add(this.state);
            } else {
                if (classList.contains(this.state)) classList.remove(this.state);
            }
        });
    }
}

class ScrollToAnchor {
    constructor(options) {
        this.nav = options.nav;
        this.topPosition = options.topPosition;
        this.animationTime = options.animationTime;

        this._run();
    }

    _run() {
        let that = this;

        $(`${this.nav} a[href^="#"]`).click(function () {
            var el = $(this).attr("href");

            $("body, html")
                .stop()
                .animate(
                    {
                        scrollTop: $(el).offset().top - that.topPosition,
                    },
                    that.animationTime
                );

            return false;
        });
    }
}

// if HTML DOM is ready
window.onload = function () {
    // options for responsiveNav
    let navOptions = {
        nav: ".main-nav", // DOM elemrnt (class or id)
        tuggleBtn: ".tuggle-btn", // DOM elemrnt (class or id)
        tuggleContent: ".tuggle-content", // DOM elemrnt (class or id)
        navLink: ".nav-link", // DOM elemrnt (class or id)
        state: "open", // Which (class or id) use to change 'nav' of page if will be the size to screen is less checkpoint.
        checkpoint: 860, // Size for screen width when we use mobile menu.
    };

    let responsiveNav = new ResponsiveNav(navOptions); // created new responsiveNav.

    // options for changeNavIfWindowScroll
    let changeNavIfWindowScrollOptions = {
        nav: ".main-nav", // DOM elemrnt (class or id)
        state: "active", // Which (class or id) use to change 'nav' of page if will be the scroll.
        heightActivateState: 150, // How many pixels will scroll page when we use the new state for nav.
    };

    // created new changeNavIfWindowScroll.
    let changeNavIfWindowScroll = new ChangeNavIfWindowScroll(
        changeNavIfWindowScrollOptions
    );

    // otptions for ScrollToAnchor
    let scrollToAnchorOptions = {
        nav: ".main-nav", // DOM elemrnt (class or id)
        topPosition: 100, // how many px to top position stop animation scroll (px)
        animationTime: 1000, // animation scroll time (ms)
    };

    // create new ScrollToAnchor
    let scrollToAnchor = new ScrollToAnchor(scrollToAnchorOptions);
};
