class design{
    /*function get day name arabic */
    static getDayName(date) {
        var day=['الاحد','الاثنين','الثلاثاء','الاربعاء','الخميس','الجمعة','السبت'];
        date=new Date(date);
        return day[date.getDay()];
    }
    /*function for reload_js*/
    /*static reload_js(src) {
        if (Array.isArray(src)){
            for (var i=0;i<src.length;i++){
                $('script[src="' + src[i] + '"]').remove();
                $('<script>').attr('src', src[i]).appendTo('head');

            }
        }else{
            $('script[src="' + src + '"]').remove();
            $('<script>').attr('src', src).appendTo('head');
        }
    }*/

    static test(message='no message',other_message='no message'){
        message=message=='no message'?message:"your message is : "+message;
        if (other_message!='no message'){
            console.log(message);
            console.log('and other message is : '+other_message);
        } else{
            console.log(message);
        }

    }
    /*show password when hover in eye*/
    static show_password(container='') {
        $(container+' i.fa-eye').hover(function () {
            $(container+'  input[data-type="password"]').attr('type','text');
            $(this).css({
                color:'#268e42'
            });
        },function () {
            $(container +' input[data-type="password"]').attr('type','password');
            $(this).css({
                color:'#495057'
            });
        }).click(function () {
            if ($(container +' input[data-type="password"]').attr('type')=='password'){
                $(container +' input[data-type="password"]').attr('type','text');
                $(this).css({
                    color:'#268e42'
                });
            } else {
                $(container +' input[data-type="password"]').attr('type','password');
                $(this).css({
                    color:'#495057'
                });
            }

        });
    }

    static dateRangFromTo(inputFrom,inputTo,containerDate,classes=''){
        $(inputFrom).addClass('input_date_range_picker');
        $(inputTo).addClass('input_date_range_picker');
        var date=new Date();
        inputFrom=$(inputFrom);
        inputTo=$(inputTo);
        containerDate=$(containerDate);
        date=date.getFullYear()+'-'+(date.getMonth()- -1)+'-'+date.getDate();
        inputFrom.val(date).prev('span').html("من "+this.getDayName(date));
        inputTo.val(date).prev('span').html("الي "+this.getDayName(date));
        Usage: containerDate
            .dateRangePicker({
                    autoClose: true,
                    singleDate :false,
                    showShortcuts: false,
                    startDate:'2019-1-7',
                    endDate:date,
                    separator : ' to ',
                    extraClass:'animated jackInTheBox border-radius faster '+classes,
                    getValue: function()
                    {
                        if (inputFrom.val() && inputTo.val() )
                            return inputFrom.val() + ' to ' + inputTo.val();
                        else
                            return '';
                    },
                    setValue: function(s,s1,s2)
                    {
                        inputFrom.val(s1);
                        inputTo.val(s2);
                        inputFrom.prev('span').html('من ' +design.getDayName(inputFrom.val()));
                        inputTo.prev('span').html('الي ' + design.getDayName(inputTo.val()));
                    }
                }
            );

        /*set day when click in input day in .uerActivity*/
        $(inputFrom).keyup(function () {
            inputFrom.prev('span').html('من ' +design.getDayName(inputFrom.val()));
        });
        $(inputTo).keyup(function () {
            inputTo.prev('span').html('الي ' +design.getDayName(inputTo.val()));
        });
    }

    static dateRang(inputDate,classes='',use_max_date=false){
        /*set date*/
        $(inputDate).addClass('input_date_range_picker');
        var date=new Date();
        inputDate=$(inputDate);
        date=date.getFullYear()+'-'+(date.getMonth()- -1)+'-'+date.getDate();
        inputDate.val(date).prev('span').html(design.getDayName(date));
        if (use_max_date){
            Usage: inputDate
                .dateRangePicker({
                        autoClose: true,
                        singleDate : true,
                        showShortcuts: false,
                        singleMonth: true,
                        startDate:'2018-1-12',
                        separator : ' to ',
                        extraClass:'animated jackInTheBox border-radius faster '+classes,
                        getValue: function()
                        {
                            if (inputDate.val() )
                                return inputDate.val();
                            else
                                return '';
                        },
                        setValue: function(s)
                        {
                            inputDate.val(s);
                            inputDate.prev('span').html(design.getDayName(s));
                        }
                    }
                );
        }else{
            Usage: inputDate
                .dateRangePicker({
                        autoClose: true,
                        singleDate : true,
                        showShortcuts: false,
                        singleMonth: true,
                        startDate:'2018-1-12',
                        endDate:date,
                        separator : ' to ',
                        extraClass:'animated jackInTheBox border-radius faster '+classes,
                        getValue: function()
                        {
                            if (inputDate.val() )
                                return inputDate.val();
                            else
                                return '';
                        },
                        setValue: function(s)
                        {
                            inputDate.val(s);
                            inputDate.prev('span').html(design.getDayName(s));
                        }
                    }
                );
        }

    }


    static useNiceScroll(){
        if (!(typeof window.orientation !== 'undefined')) {
            $("body").niceScroll({
                cursorcolor: "#08526D",
                cursorwidth: "8px",
                cursorminheight: 100,
                cursorborder: "1px solid #08526D"

            });
        }
    }
    static updateNiceScroll(waitingTimeBeforeUpdateByMilliSecond=0){
        if (!(typeof window.orientation !== 'undefined')) {
            if (waitingTimeBeforeUpdateByMilliSecond==0){
                $("body").getNiceScroll().resize();
            }else{
                setTimeout(function()
                {
                    $("body").getNiceScroll().resize();
                }, waitingTimeBeforeUpdateByMilliSecond);
            }
        }
    }

    static useToolTip(){
        // $('.tooltips').tooltip();
        $('.tooltips').tooltip({
            container:'body',
        });
    }

    static useSound(type='success'){//type success,error,info
        if ($('#sound_success').length!=0){
            if ($('#input_sound_value').length!=0){
                if (Cookie.get('sound_value')>0){
                    $('#sound_'+type)[0].volume=(Cookie.get('sound_value') / 10);
                }else {
                    $('#sound_'+type)[0].volume=0.7;
                }
            }
            $('#sound_'+type)[0].currentTime=0;
            $('#sound_'+type)[0].play();
        }
    }

    static check_submit(form,e/*,state=1*/){
        //state =1 for remove submit,0 for allow submit
        /*if (state==0){
            form.removeAttr('data-submit');
        }*/
        $('#load').css('display', 'block');
        if (form.is('[data-submit]')){
            e.preventDefault();
            design.useSound('error');
            if (form.is('[data-submit-again]')) {
                if (form.is('[data-submit-again1]')){
                    e.preventDefault();
                    alertify.log('حدث خطاء فى العملية أعد تحميل الصفحة لحل الخطاء سبب الخطاء بطى فى السيرفر أو فتح الصفحة بطريقة غير صحيحة','error',0);
                    /*if (form.is('[data-submit-lastTime]')){
                        form.submit();
                    }else{
                        e.preventDefault();
                        $(this).confirm({
                            text: "هل تريد تنفيذ هذة العملية؟",
                            title: "إعادة تنفيذ عملية",
                            confirm: function (button) {
                                form.attr('data-submit-lastTime','true');
                            },
                            cancel: function (button) {

                            },
                            post: true,
                            confirmButtonClass: "btn-danger",
                            cancelButtonClass: "btn-default",
                            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                        });
                    }
                    form.attr('data-submit-again2','disabled');*/

                }else{
                    e.preventDefault();
                    form.attr('data-submit-again1','disabled');
                    alertify.log('برجاء الإنتظار لعدة لحظات','error',0);
                }
            }else{
                e.preventDefault();
                form.attr('data-submit-again','disabled');
                alertify.log('برجاء الإنتظار لعدة ثوانى','error',0);
            }

        }else{
            form.attr('data-submit','disabled');
        }
    }

    static hide_option_not_exist_in_table_in_select(select,tr,td_index,refreshSelectPicker=false){
        select.children().each(function () {
            var option_val=$(this).val();
            var checkExist=false;
            tr.each(function () {
                if (($(this).children().eq(td_index).html()).search(option_val)!=-1){
                    checkExist=true;
                }
            });
            if (checkExist){
                $(this).removeClass('d-none');
            }else{
                $(this).addClass('d-none');
            }
        });
        if (refreshSelectPicker){
            select.selectpicker('refresh');
        }
    }

    static disable_input_submit_when_enter(el){
        $(el).on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                //select next element
                /*$('input, select, textarea')
                    [$('input,select,textarea').index(this)+1].focus();*/

                return false;
            }
        });
    }

    static click_when_key_add(button,el='body'){
        $(el).on('keydown', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 107) {
                e.preventDefault();
                if ($('#load').css('display')=='block'){
                    alertify.success('برجاء الإنتظار... ');
                    design.useSound('info');
                }else{
                    $(button).trigger('click');
                }
                return false;
            }
        });
    }


    // static toggleFullscreen(state='auto',event='') {
    static toggleFullscreen(event) {
        var element = document.documentElement;

        if (event instanceof HTMLElement) {
            element = event;
        }

        var isFullscreen = document.webkitIsFullScreen || document.mozFullScreen || false;

        element.requestFullScreen = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || function () { return false; };
        document.cancelFullScreen = document.cancelFullScreen || document.webkitCancelFullScreen || document.mozCancelFullScreen || function () { return false; };

        // state=='auto'?(isFullscreen ? document.cancelFullScreen() : element.requestFullScreen()):(!state?document.cancelFullScreen() : element.requestFullScreen());
        isFullscreen ? document.cancelFullScreen() : element.requestFullScreen();
    }
}


function roundTo(num,fraction=2){
    num +='';
    var dot=num.indexOf('.');
    var trunc=Math.trunc(num);
    if (trunc==0 && num < 0 && fraction!=0){
        trunc='-'+trunc;
    }

    var newNum='';
    for (var i = 0; i < fraction && i < ((num.length - (trunc+'').length) -1); i++) {
        newNum += num[dot+1+i];
    }
    return (newNum*1==0?trunc:trunc+'.'+newNum)*1;
}


