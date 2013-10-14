'use strict'

var leoht = leoht || {}

// include jQuery if it is not already included.
window.jQuery || document.write('<script src="http://code.jquery.com/jquery-latest.min.js" ></script>');

(function (window) {

    leoht.AjaxSearch = {}

    leoht.AjaxSearch.init = function () {

        var $form = $('form[data-ajaxsearch-form]')
        var $input = $form.find('input[type=text]')

        var url = $form.attr('action')

        $input.keyup(function () {
            var q = $input.val()

            if (q.length > 0) {
                leoht.AjaxSearch.getResults(url, q)
            } else {
                $('*[data-ajaxsearch-results]').fadeOut(200);
            }
        })
    }

    leoht.AjaxSearch.getResults = function (url, query) {

        var $results = $('*[data-ajaxsearch-results]')

        try {
            $.getJSON(url+query, function (data) {

                if (data.length > 0) {
                    var resultHtml = '';
                    $.each(data, function (i, el) {
                        var resultBody = ''
                        $.each(el, function (key, value) {
                            if (0 > key.indexOf('_') && key != 'id')
                                resultBody += '<span data-ajaxsearch-result-'+key+' >'+ value +'</span> '
                        })
                        if (el._link) {
                            resultBody = '<a href="'+el._link+'" >'+resultBody+'</a>'
                        }
                        resultHtml += '<div data-ajaxsearch-result >'+resultBody+'</div>'
                    }) 
                } else {
                    resultHtml = $('*[data-ajaxsearch-result]').attr('data-noresult-msg') || '<div data-ajaxsearch-result >No result</div>'
                }

                $results.fadeIn(200).html(resultHtml)
                
            })
        } catch(e) {
            $results.fadeIn(200).html('An error has occured.');
        }

    }

    if (typeof window !== 'undefined') {
        window.AjaxSearch = leoht.AjaxSearch
    }

}) (window)
