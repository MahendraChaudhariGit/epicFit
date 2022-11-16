var UINestable = function () {
	"use strict";
    //function to initiate jquery.nestable
    var updateOutput = function (e) {
        var list = e.length ? e : $(e.target);

        var output = list.next();
        //console.log(output)
            //output = $('input[name="goalFitnessComponents"]');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));
            //, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };
    var runNestable = function () {
        $('#nestable').nestable({
            group: 1,
            maxDepth: 2
        }).on('change', updateOutput);

        /*// activate Nestable for list 2
        $('#nestable2').nestable({
            group: 1
        }).on('change', updateOutput);
        // output initial serialised data*/
        updateOutput($('#nestable').data('output', $('input[name="goalFitnessComponents"]')));
        /*updateOutput($('#nestable2').data('output', $('#nestable2-output')));
        $('#nestable-menu').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
        $('#nestable3').nestable();*/

        $('#salesNestable').nestable({
            group: 1,
            maxDepth: 2
        }).on('change', updateOutput);
        updateOutput($('#salesNestable').data('output', $('input[name="salesNestable"]')));
    };
    return {
        //main function to initiate template pages
        init: function () {
            runNestable();
        },

        update: function(elem){
            updateOutput(elem);
        }
    };
}();