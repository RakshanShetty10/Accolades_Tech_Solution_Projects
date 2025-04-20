"use strict"

var body = $('body');
var html = $('html');

function dzSettings({language, typography, version, layout, navheaderBg, headerBg, sidebarStyle, sidebarBg, sidebarPosition, headerPosition, containerLayout, primary, secondary}) {
    this.language = language || "en_GB";
    this.typography = typography || "poppins";
    this.version = version || "light";
    this.layout = layout || "vertical";
    this.navheaderBg = navheaderBg || "color_1";
    this.headerBg = headerBg || "color_1";
    this.sidebarStyle = sidebarStyle || "full";
    this.sidebarBg = sidebarBg || "color_1";
    this.sidebarPosition = sidebarPosition || "static";
    this.headerPosition = headerPosition || "static";
    this.containerLayout = containerLayout || "wide";
    //this.direction = direction || "ltr";
	this.primary = primary || "color_1";
	this.secondary = secondary || "color_1";

    this.manageLanguage();
    this.manageTypography();
    this.manageVersion();
    this.manageLayout();
    this.manageNavHeaderBg();
    this.manageHeaderBg();
    this.manageSidebarStyle();
    this.manageSidebarBg();
    this.manageSidebarPosition();
    this.manageHeaderPosition();
    this.manageContainerLayout();
    //this.manageRtlLayout();
    this.manageResponsiveSidebar();
	this.managePrimaryColor();
	this.manageSecondaryColor();
}

dzSettings.prototype.manageLanguage = function() {
    switch(this.language) {
		case "pt_BR": 
            body.attr("data-language", "pt_BR");
            break;
        case "fr_FR": 
            body.attr("data-language", "fr_FR");
            break;
        case "de_DE": 
            body.attr("data-language", "de_DE");
            break;
		case "en_GB": 
            body.attr("data-language", "en_GB");
            break;
		case "hi_IN": 
            body.attr("data-language", "hi_IN");
            break;
		case "it_IT": 
            body.attr("data-language", "it_IT");
            break;
		case "jp_JP": 
            body.attr("data-language", "jp_JP");
            break;
		case "es_ES": 
            body.attr("data-language", "es_ES");
            break;
		case "ar_TN": 
            body.attr("data-language", "ar_TN");
            break;
		case "tr_TR": 
            body.attr("data-language", "tr_TR");
            break;
        default: 
            body.attr("data-language", "en_GB");
    }
}
dzSettings.prototype.manageVersion = function() {
    switch(this.version) {
        case "light": 
            body.attr("data-theme-version", "light");
            body.attr("data-bs-theme", "light");
            break;
        case "dark": 
            body.attr("data-theme-version", "dark");
            body.attr("data-bs-theme", "dark");
            break;
        case "transparent": 
            body.attr("data-theme-version", "transparent");
            break;
        default: 
            body.attr("data-theme-version", "light");
            body.attr("data-bs-theme", "light");
    }
}
dzSettings.prototype.manageTypography = function() {
    switch(this.typography) {
		case "cairo": 
            body.attr("data-typography", "cairo");
            break;
        case "poppins": 
            body.attr("data-typography", "poppins");
            break;
        case "roboto": 
            body.attr("data-typography", "roboto");
            break;
        case "opensans": 
            body.attr("data-typography", "opensans");
            break;
		case "helveticaneue": 
            body.attr("data-typography", "helveticaneue");
            break;
        default: 
            body.attr("data-typography", "poppins");
    }
}
dzSettings.prototype.manageLayout = function() {
    switch(this.layout) {
        case "horizontal": 
            this.sidebarStyle === "overlay" ? body.attr("data-sidebar-style", "full") : body.attr("data-sidebar-style", `${this.sidebarStyle}`);
            body.attr("data-layout", "horizontal");
            break;
        case "vertical": 
            body.attr("data-layout", "vertical");
            break;
        default:
            body.attr("data-layout", "vertical");
    }
}
dzSettings.prototype.manageNavHeaderBg = function() {
    switch(this.navheaderBg) {
        case "color_1": 
            body.attr("data-nav-headerbg", "color_1");
            break;
        case "color_2": 
            body.attr("data-nav-headerbg", "color_2");
            break;
        case "color_3": 
            body.attr("data-nav-headerbg", "color_3");
            break;
        case "color_4": 
            body.attr("data-nav-headerbg", "color_4");
            break;
        case "color_5": 
            body.attr("data-nav-headerbg", "color_5");
            break;
        case "color_6": 
            body.attr("data-nav-headerbg", "color_6");
            break;
        case "color_7": 
            body.attr("data-nav-headerbg", "color_7");
            break;
        case "color_8": 
            body.attr("data-nav-headerbg", "color_8");
            break;
        case "color_9": 
            body.attr("data-nav-headerbg", "color_9");
            break;
        case "color_10": 
            body.attr("data-nav-headerbg", "color_10");
            break;
		case "color_11": 
            body.attr("data-nav-headerbg", "color_11");
            break;
		case "color_12": 
            body.attr("data-nav-headerbg", "color_12");
            break;
		case "color_13": 
            body.attr("data-nav-headerbg", "color_13");
            break;
		case "color_14": 
            body.attr("data-nav-headerbg", "color_14");
            break;
		case "color_15": 
            body.attr("data-nav-headerbg", "color_15");
            break;		
        case "image_1": 
            body.attr("data-nav-headerbg", "image_1");
            break;
        case "image_2": 
            body.attr("data-nav-headerbg", "image_2");
            break;
        case "image_3": 
            body.attr("data-nav-headerbg", "image_3");
            break;
        default:
            body.attr("data-nav-headerbg", "color_1");
    }
}
dzSettings.prototype.manageHeaderBg = function() {
    switch(this.headerBg) {
        case "color_1": 
            body.attr("data-headerbg", "color_1");
            break;
        case "color_2": 
            body.attr("data-headerbg", "color_2");
            break;
        case "color_3": 
            body.attr("data-headerbg", "color_3");
            break;
        case "color_4": 
            body.attr("data-headerbg", "color_4");
            break;
        case "color_5": 
            body.attr("data-headerbg", "color_5");
            break;
        case "color_6": 
            body.attr("data-headerbg", "color_6");
            break;
        case "color_7": 
            body.attr("data-headerbg", "color_7");
            break;
        case "color_8": 
            body.attr("data-headerbg", "color_8");
            break;
        case "color_9": 
            body.attr("data-headerbg", "color_9");
            break;
        case "color_10": 
            body.attr("data-headerbg", "color_10");
            break;
		case "color_11": 
            body.attr("data-headerbg", "color_11");
            break;
		case "color_12": 
            body.attr("data-headerbg", "color_12");
            break;
		case "color_13": 
            body.attr("data-headerbg", "color_13");
            break;
		case "color_14": 
            body.attr("data-headerbg", "color_14");
            break;
		case "color_15": 
            body.attr("data-headerbg", "color_15");
            break;		
        case "transparent": 
            body.attr("data-headerbg", "transparent");
            break;
        case "gradient_1": 
            body.attr("data-headerbg", "gradient_1");
            break;
        case "gradient_2": 
            body.attr("data-headerbg", "gradient_2");
            break;
        case "gradient_3": 
            body.attr("data-headerbg", "gradient_3");
            break;
        default:
            body.attr("data-headerbg", "color_1");
    }
}
dzSettings.prototype.manageSidebarStyle = function() {
    switch(this.sidebarStyle) {
        case "full":
            body.attr("data-sidebar-style", "full");
            break;
        case "mini":
            body.attr("data-sidebar-style", "mini");
            break;
        case "compact":
            body.attr("data-sidebar-style", "compact");
            break;
        case "modern":
            body.attr("data-sidebar-style", "modern");
            break;
        case "icon-hover":
            body.attr("data-sidebar-style", "icon-hover");
			$("body").on("mouseenter", ".deznav", function(){
				$('#main-wrapper').addClass('iconhover-toggle');
			}).on("mouseleave", ".deznav", function(){
				$('#main-wrapper').removeClass('iconhover-toggle');
			});
			break;
        case "overlay":
            this.layout === "horizontal" ? body.attr("data-sidebar-style", "full") : body.attr("data-sidebar-style", "overlay");
            break;
        default:
            body.attr("data-sidebar-style", "full");
    }
}
dzSettings.prototype.manageSidebarBg = function() {
    switch(this.sidebarBg) {
        case "color_1": 
            body.attr("data-sidebarbg", "color_1");
            break;
        case "color_2": 
            body.attr("data-sidebarbg", "color_2");
            break;
        case "color_3": 
            body.attr("data-sidebarbg", "color_3");
            break;
        case "color_4": 
            body.attr("data-sidebarbg", "color_4");
            break;
        case "color_5": 
            body.attr("data-sidebarbg", "color_5");
            break;
        case "color_6": 
            body.attr("data-sidebarbg", "color_6");
            break;
        case "color_7": 
            body.attr("data-sidebarbg", "color_7");
            break;
        case "color_8": 
            body.attr("data-sidebarbg", "color_8");
            break;
        case "color_9": 
            body.attr("data-sidebarbg", "color_9");
            break;
        case "color_10": 
            body.attr("data-sidebarbg", "color_10");
            break;
		case "color_11": 
            body.attr("data-sidebarbg", "color_11");
            break;
		case "color_12": 
            body.attr("data-sidebarbg", "color_12");
            break;
		case "color_13": 
            body.attr("data-sidebarbg", "color_13");
            break;
		case "color_14": 
            body.attr("data-sidebarbg", "color_14");
            break;
		case "color_15": 
            body.attr("data-sidebarbg", "color_15");
            break;			
        case "image_1": 
            body.attr("data-sidebarbg", "image_1");
            break;
        case "image_2": 
            body.attr("data-sidebarbg", "image_2");
            break;
        case "image_3": 
            body.attr("data-sidebarbg", "image_3");
            break;
        default:
            body.attr("data-sidebarbg", "color_1");
    }
}
dzSettings.prototype.manageSidebarPosition = function() {
    switch(this.sidebarPosition) {
        case "fixed": 
            this.sidebarStyle === "overlay" && this.layout === "vertical" || this.sidebarStyle === "modern" ? body.attr("data-sidebar-position", "static") : body.attr("data-sidebar-position", "fixed");
            break;
        case "static": 
            body.attr("data-sidebar-position", "static");
            break;
        default: 
            body.attr("data-sidebar-position", "static");       
    }
}
dzSettings.prototype.manageHeaderPosition = function() {
    switch(this.headerPosition) {
        case "fixed": 
            body.attr("data-header-position", "fixed");
            break;
        case "static": 
            body.attr("data-header-position", "static");
            break;
        default: 
            body.attr("data-header-position", "static");       
    }
}
dzSettings.prototype.manageContainerLayout = function() {
    switch(this.containerLayout) {
        case "boxed":
            if(this.layout === "vertical" && this.sidebarStyle === "full") {
                body.attr("data-sidebar-style", "overlay");
            }
            body.attr("data-container", "boxed");
            break;
        case "wide":
            body.attr("data-container", "wide");
            break;
        case "wide-boxed": 
            body.attr("data-container", "wide-boxed");
            break;
        default:
            body.attr("data-container", "wide");
    }
}
dzSettings.prototype.manageRtlLayout = function() {
	switch(this.direction) {
        case "rtl":
            html.attr("dir", "rtl");
            html.addClass('rtl');
            body.attr("direction", "rtl");
            break;
        case "ltr": 
            html.attr("dir", "ltr");
            html.removeClass('rtl');
            body.attr("direction", "ltr");
            break;
        default: 
            html.attr("dir", "ltr");
            body.attr("direction", "ltr");
    }
}
dzSettings.prototype.manageResponsiveSidebar = function() {
    const innerWidth = $(window).innerWidth();
    if(innerWidth < 1024) {
        body.attr("data-layout", "vertical");
        body.attr("data-container", "wide");
    }

    if(innerWidth > 767 && innerWidth < 1024) {
        body.attr("data-sidebar-style", "mini");
    }

    if(innerWidth < 768) {
        body.attr("data-sidebar-style", "overlay");
    }
}
dzSettings.prototype.managePrimaryColor = function() {
	switch(this.primary) {
        case "color_1": 
            body.attr("data-primary", "color_1");
			 break;
        case "color_2": 
            body.attr("data-primary", "color_2");
            break;
		case "color_3": 
            body.attr("data-primary", "color_3");
            break;
		case "color_4": 
            body.attr("data-primary", "color_4");
            break;
		case "color_5": 
            body.attr("data-primary", "color_5");
            break;
		case "color_6": 
            body.attr("data-primary", "color_6");
            break;	
		case "color_7": 
            body.attr("data-primary", "color_7");
            break;
		case "color_8": 
            body.attr("data-primary", "color_8");
            break;
		case "color_9": 
            body.attr("data-primary", "color_9");
            break;
		case "color_10": 
            body.attr("data-primary", "color_10");
            break;
		case "color_11": 
            body.attr("data-primary", "color_11");
            break;
		case "color_12": 
            body.attr("data-primary", "color_12");
            break;
		case "color_13": 
            body.attr("data-primary", "color_13");
            break;
		case "color_14": 
            body.attr("data-primary", "color_14");
            break;
		case "color_15": 
            body.attr("data-primary", "color_15");
            break;	
			
        default:
            body.attr("data-primary", "color_1");
    }
}
dzSettings.prototype.manageSecondaryColor = function() {
	switch(this.secondary) {
        case "color_1": 
            body.attr("data-secondary", "color_1");
			 break;
        case "color_2": 
            body.attr("data-secondary", "color_2");
            break;
		case "color_3": 
            body.attr("data-secondary", "color_3");
            break;
		case "color_4": 
            body.attr("data-secondary", "color_4");
            break;
		case "color_5": 
            body.attr("data-secondary", "color_5");
            break;
		case "color_6": 
            body.attr("data-secondary", "color_6");
            break;	
		case "color_7": 
            body.attr("data-secondary", "color_7");
            break;
		case "color_8": 
            body.attr("data-secondary", "color_8");
            break;
		case "color_9": 
            body.attr("data-secondary", "color_9");
            break;
		case "color_10": 
            body.attr("data-secondary", "color_10");
            break;
		case "color_11": 
            body.attr("data-secondary", "color_11");
            break;
		case "color_12": 
            body.attr("data-secondary", "color_12");
            break;
		case "color_13": 
            body.attr("data-secondary", "color_13");
            break;
		case "color_14": 
            body.attr("data-secondary", "color_14");
            break;
		case "color_15": 
            body.attr("data-secondary", "color_15");
            break;	
			
        default:
            body.attr("data-secondary", "color_1");
    }
}



var dzSettingsOptions = {};
function getUrlParams(dParam){
	var dPageURL = window.location.search.substring(1),
		dURLVariables = dPageURL.split('&'),
		dParameterName,
		i;

	for (i = 0; i < dURLVariables.length; i++) {
		dParameterName = dURLVariables[i].split('=');

		if (dParameterName[0] === dParam) {
			return dParameterName[1] === undefined ? true : decodeURIComponent(dParameterName[1]);
		}
	}
}

(function($) {
	"use strict"
	
	dzSettingsOptions = {
		language: "en_GB",
		typography: "poppins",
		version: "light",
		layout: "vertical",
		primary: "color_1",
		headerBg: "color_4",
		navheaderBg: "color_4",
		sidebarBg: "color_1",
		sidebarStyle: "full",
		sidebarPosition: "fixed",
		headerPosition: "fixed",
		containerLayout: "full",
	};
	
	new dzSettings(dzSettingsOptions); 
	jQuery(window).on('resize',function(){
        dzSettingsOptions.containerLayout = $('#container_layout').val();
        new dzSettings(dzSettingsOptions); 
	});
	
	jQuery(document).ready(function() {
		$('.ltool-language').on('click', function(){
			var dataLang = $(this).attr("class").split(' ')[1];
			body.attr('data-language', dataLang);
			setCookie('language', dataLang);
		});
		
		$('#selectLanguageDropdown').on('change', function() {
			var dataLang = $(this).val();
			body.attr('data-language', dataLang);
			setCookie('language', dataLang);
			$('#selectLanguageDropdown').removeClass('ltool-is-visible');
		});
		// change the theme version controller
		
	});

})(jQuery);


/* Cookies Function */
function setCookie(cname, cvalue, exhours) {
	var d = new Date();
	d.setTime(d.getTime() + (30*60*1000)); /* 30 Minutes */
	var expires = "expires="+ d.toString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname){
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function deleteCookie(cname){
	var d = new Date();
	d.setTime(d.getTime() + (1));
	var expires = "expires="+ d.toString();
	document.cookie = cname + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT"+";path=/";
}

function deleteAllCookie(reload = true){
	jQuery.each(themeOptionArr, function(optionKey, optionValue) {
		deleteCookie(optionKey);
	});
	if(reload){
		location.reload();
	}
} 	
/* Cookies Function END */