'use strict'

var leoht = leoht || {}

// include jQuery if it is not already included.
window.jQuery || document.write('<script src="http://code.jquery.com/jquery-latest.min.js" ></script>');

(function (window) {

    leoht.AjaxSearch = {}

    leoht.AjaxSearch.init = function (onResultCallback) {

        onResultCallback = typeof onResultCallback !== 'undefined' ? onResultCallback : null

        var $form = $('form[data-ajaxsearch-form]')
        var $input = $form.find('input[type=text]')
        var $engine = $form.find('input[name=_engine]')

        var engine = $engine ? $engine.val() : 'main'

        var url = $form.attr('action')

        $input.keyup(function () {
            var q = $input.val()

            if (q.length > 0) {
                leoht.AjaxSearch.getResults(url, q, engine, onResultCallback)
            } else {
                $('*[data-ajaxsearch-results]').fadeOut(200);
            }
        })
    }

    leoht.AjaxSearch.getResults = function (url, query, engine, callback) {

        var $results = $('*[data-ajaxsearch-results]')

        try {
            $.getJSON(url+query, { _engine: engine }, function (data) {

                if (data.length > 0) {
                    var resultHtml = '';
                    $.each(data, function (i, el) {

                        if (null != callback) {
                            callback(el)
                        } else {
                            var resultBody = ''
                            var i = 0
                            $.each(el, function (key, value) {
                                if (0 > key.indexOf('_') && key != 'id') {
                                    resultBody += '<span data-ajaxsearch-result-'+key+' >'+ value +'</span> '
                                    if (i < Object.keys(el).length-1 )
                                        resultBody += ' - '
                                }
                                
                                i++
                            })
                            if (el._link) {
                                resultBody = '<a href="'+el._link+'" >'+resultBody+'</a>'
                            }
                            resultHtml += '<div data-ajaxsearch-result >'+resultBody+'</div>'
                        }  
                    }) 
                } else {
                    var msg = $('*[data-ajaxsearch-results]').attr('data-noresult-msg')
                    resultHtml = msg || 'No result'
                    resultHtml = '<div data-ajaxsearch-result >'+resultHtml+'</div>'
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
