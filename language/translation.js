var arrLang = {
    'en'  : {
        'ranking' : 'Ranking',
        'about' : 'About',
        'stats' : 'Stats',
        'extra' : 'Extras'
    },
    'et' : {
        'ranking' : 'Edetabel',
        'about' : 'Leheküljest',
        'stats' : 'Statistika',
        'extra' : 'Lisad'
    }
};

function onPageLoad(){
    var trl;
    if(sessionStorage.getItem("lang")) {
        trl = sessionStorage.getItem("lang");
    }
    else{
        trl = "en";
    }

    $('.trl').each(function(){
        $(this).text(arrLang[trl][$(this).attr('key')]);
    });

}

// When the page has been loaded
$(document).ready(function(){
    onPageLoad();
});

$(function translate() {
    $('.translate').click(function (){

        var trl = $(this).attr('id');
        sessionStorage.setItem("lang", trl);

        $('.trl').each(function(){
            $(this).text(arrLang[trl][$(this).attr('key')]);
        });
    });
});