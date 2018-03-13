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

$(function() {
    $('.translate').click(function (){
        var trl = $(this).attr('id');

        $('.trl').each(function(index,element){
            $(this).text(arrLang[trl][$(this).attr('key')]);
        });
    });
});