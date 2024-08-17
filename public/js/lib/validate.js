var defaultErrorMessage={
    required:'هذا الحقل اجباري ',
    numeric:'من فضلك استخدم رقم صحيح',
    gt:'من فضلك استخدم رقم اكبر من',
    gtInt:'من فضلك استخدم رقم صحيح اكبر من',
    lt:'من فضلك استخدم رقم اصغر من',
    ltInt:'من فضلك استخدم رقم صحيح اصغر من',
    bool:'من فضلك استخدم true|false',
    min:'اقل عدد من الحروف هو ',
    max:'اكبر عدد من الحروف هو ',
    phone:'من فضلك ادخل رقم هاتف صحيح ',
    email:'من فضلك ادخل ايميل صحيح ',
    price:'ادخل مبلغ حقيقى ',
    negative_price:'ادخل مبلع حقيقى موجب أو سالب ',
    qte:'ادخل كمية صحيح ',
    tel:'من فضلك إدخل رقم هاتف صحيح',
    regex:'من فضلك ادخل البيانات بصيغة صحيحة ',
};
/*
* required:message (no pattern)
* numeric:message  (with pattern)
* gt:val:message (no pattern)
* gtInt:val:message (no pattern)
* lt:val:message (no pattern)
* ltInt:val:message (no pattern)
* bool:message  (with pattern)
* min:val:message (no pattern)
* max:val:message (no pattern)
* phone:type:message (with pattern)
* email:message (with pattern)
* price:message (with pattern)
* negative_price:message (with pattern)
* qte:message (with pattern)
* tel:message (with pattern)
* regex:val:message (with pattern)
* */

function validate(Element,validation,patternType='') { /*item type not important if you use id*/

    //check if element don't exist in dom
    if (Element.length == 0 ){
        return;
    }
    var itemVal=Element.val();

    var errors=Element.siblings().filter('div.invalid-feedback');/*to store all div errors for this item*/
    validation=validation.split('|');
    var hasAnyError=false; /*to check if item has any error*/
    for (var i = 0; i < validation.length; i++) {
        var data=validation[i].split(':');

        /*..................................validation rules*/

        /*required:message*/
        if (data[0]=='required'){
            Element.attr('required','required');
            if (patternType=='required'){
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.required);
                }
            }
            if (itemVal.trim().length>0) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-required')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-required')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-required">'+data[1]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-required">'+defaultErrorMessage.required+'</div>')
                    }
                }
            }
        }
        else
        /*numeric:message*/
        if (data[0]=='numeric'){
            var rule=/^[-]?[0-9]+$/;
            if (patternType=='numeric'){
                Element.attr('pattern',rule.source);
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.numeric);
                }
            }
            if (rule.test(itemVal)) { // validation success ,remove error if exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-numeric')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-numeric')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-numeric">'+data[1]+'</div>')
                    }else{
                        Element.after('<div class="invalid-feedback error-numeric">'+defaultErrorMessage.numeric+'</div>')
                    }
                }
            }
        }
        else /*gt:val:message*/
        if (data[0]=='gt'){
            var rule=/^[-]?([0]|([1-9][0-9]{0,5}))([.][0-9]+)?$/;
            if (patternType=='gt'){
                if (data.length > 2){
                    Element.attr('title',data[2]);
                }else{
                    Element.attr('title',defaultErrorMessage.gt +' '+data[1]);
                }
            }
            if (rule.test(itemVal) && itemVal*1 > data[1]) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-gt')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-gt')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-gt">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-gt">'+defaultErrorMessage.gt +' '+data[1]+'</div>')
                    }
                }
            }
        }
        else /*gtInt:val:message*/
        if (data[0]=='gtInt'){
            var rule=/^[-]?[0-9]+$/;
            if (patternType=='gtInt'){
                if (data.length > 2){
                    Element.attr('title',data[2]);
                }else{
                    Element.attr('title',defaultErrorMessage.gtInt +' '+data[1]);
                }
            }
            if (rule.test(itemVal) && itemVal*1 > data[1]) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-gtInt')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-gtInt')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-gtInt">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-gtInt">'+defaultErrorMessage.gtInt +' '+data[1]+'</div>')
                    }
                }
            }
        }
        else /*lt:val:message*/
        if (data[0]=='lt'){
            var rule=/^[-]?[0-9]+([.][0-9]+)?$/;
                if (patternType=='lt'){
                    if (data.length > 2){
                        Element.attr('title',data[2]);
                    }else{
                        Element.attr('title',defaultErrorMessage.lt+' '+data[1]);
                    }
                }
            if (rule.test(itemVal) && itemVal*1 < data[1]) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-lt')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-lt')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-lt">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-lt">'+defaultErrorMessage.lt +' '+data[1]+'</div>')
                    }
                }
            }
        }
        else /*ltInt:val:message*/
        if (data[0]=='ltInt'){
            var rule=/^[-]?[0-9]+$/;
            if (patternType=='ltInt'){
                if (data.length > 2){
                    Element.attr('title',data[2]);
                }else{
                    Element.attr('title',defaultErrorMessage.ltInt+' '+data[1]);
                }
            }
            if (rule.test(itemVal) && itemVal*1 < data[1]) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-ltInt')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-ltInt')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-ltInt">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-ltInt">'+defaultErrorMessage.ltInt +' '+data[1]+'</div>')
                    }
                }
            }
        }
        else /*bool:message*/
        if (data[0]=='bool'){
            var rule=/^(true)|(True)|(false)|(False)$/;
            if (patternType=='bool'){
                Element.attr('pattern',rule.source);
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.bool);
                }
            }
            if (rule.test(itemVal)){
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-bool')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-bool')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-bool">'+data[1]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-bool">'+defaultErrorMessage.bool +'</div>')
                    }
                }
            }
        }
        else /*min:val:message*/
        if (data[0]=='min'){
            var tempRule=/^./;
            var rule= new RegExp(tempRule.source+'{'+data[1]+',}');
            if (patternType=='min'){
                Element.attr('pattern',rule.source);
                if (data.length > 2){
                    Element.attr('title',data[2]);
                }else{
                    Element.attr('title',defaultErrorMessage.min+data[1]);
                }
            }
            if (itemVal.length >= data[1]) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-min')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-min')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-min">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-min">'+defaultErrorMessage.min +' '+data[1]+'</div>')
                    }
                }
            }
        }
        else /*max:val:message*/
        if (data[0]=='max'){
            if (patternType=='max'){
                if (data.length > 2){
                    Element.attr('title',data[2]);
                }else{
                    Element.attr('title',defaultErrorMessage.max+data[1]);
                }
            }
            if (itemVal.length <= data[1]) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-max')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-max')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-max">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-max">'+defaultErrorMessage.max+' '+data[1]+'</div>')
                    }
                }
            }
        }
        else /*phone:type:message*/
        if (data[0]=='phone'){
            var rule=/^[0][1][0-2][0-9]{8}$/;
            if (patternType=='phone'){
                Element.attr('pattern',rule.source);
                if (data.length > 2){
                    Element.attr('title',data[2]);
                }else{
                    Element.attr('title',defaultErrorMessage.phone);
                }
            }
            if (rule.test(itemVal)) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-phone')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-phone')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-phone">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-phone">'+defaultErrorMessage.phone+'</div>')
                    }
                }
            }
        }
        else /*email:message*/
        if (data[0]=='email'){
            var rule=/^[\w-\.]+@([\w]+\.)+[\w-]{2,4}$/;
            if (patternType=='email'){
                Element.attr('pattern',rule.source);
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.email);
                }
            }
            if (rule.test(itemVal)) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-email')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-email')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-email">'+data[1]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-email">'+defaultErrorMessage.email+'</div>')
                    }
                }
            }
        }
        else /*price:message*/
        if (data[0]=='price'){
            var rule=/^([0]|([1-9][0-9]{0,5}))([.][0-9][0-9]?)?$/;
            if (Element.is('[data-small_price]')){
                rule=/^([0]|([1-9][0-9]{0,5}))([.][0-9][0-9]?[0-9]?)?$/;
            }
            // var rule=/^[0-9]{1,6}([.][0-9][0-9]?)?$/;
            if (patternType=='price'){
                Element.attr('pattern',rule.source);
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.price);
                }
            }
            if (rule.test(itemVal)) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-price')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-price')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-price">'+data[1]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-price">'+defaultErrorMessage.price+'</div>')
                    }
                }
            }
        }
        else /*negative_price:message*/
        if (data[0]=='negative_price'){
            var rule=/^([-]?)([0]|([1-9][0-9]{0,5}))([.][0-9][0-9]?)?$/;
            // var rule=/^([-]?)[0-9]{1,6}([.][0-9][0-9]?)?$/;
            if (patternType=='negative_price'){
                Element.attr('pattern',rule.source);
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.negative_price);
                }
            }
            if (rule.test(itemVal)) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-negative_price')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-negative_price')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-negative_price">'+data[1]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-negative_price">'+defaultErrorMessage.negative_price+'</div>')
                    }
                }
            }
        }
        else /*qte:message*/
        if (data[0]=='qte'){
            var rule=/^([0]|([1-9][0-9]{0,5}))([.][0-9][0-9]?[0-9]?)?$/;
            // var rule=/^[0-9]{1,6}([.][0-9][0-9]?[0-9]?)?$/;
            if (patternType=='qte'){
                Element.attr('pattern',rule.source);
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.qe);
                }
            }
            if (rule.test(itemVal)) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-qte')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-qte')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-qte">'+data[1]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-qte">'+defaultErrorMessage.qte+'</div>')
                    }
                }
            }
        }
        else /*tell:message*/
        if (data[0]=='tel'){
            var rule=/^[0][1]([0-2]|[5])[0-9]{8}$/;
            if (patternType=='tel'){
                Element.attr('pattern',rule.source);
                if (data.length > 1){
                    Element.attr('title',data[1]);
                }else{
                    Element.attr('title',defaultErrorMessage.tel);
                }
            }
            if (rule.test(itemVal)) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-tel')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-tel')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 1){
                        Element.after('<div class="invalid-feedback error-tel">'+data[1]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-tel">'+defaultErrorMessage.tel+'</div>')
                    }
                }
            }
        }
        else /*regex:val:message*/
        if (data[0]=='regex'){
            var rule = new RegExp(data[1], "gi");
            if (patternType=='regex'){
                Element.attr('pattern',rule.source);
                if (data.length > 2){
                    Element.attr('title',data[2]);
                }else{
                    Element.attr('title',defaultErrorMessage.regex);
                }
            }
            if (rule.test(itemVal)) {
                for (var j = 0; j < errors.length; j++) { // validation success ,remove error if exist before
                    if (errors.eq(j).hasClass('error-regex')){
                        errors.eq(j).remove();
                    }
                }
            }else {
                hasAnyError=true;
                var existError='no'; //validation error ,check if this error is exist before
                for (var j = 0; j < errors.length; j++) {
                    if (errors.eq(j).hasClass('error-regex')){
                        existError=true;
                    }
                }
                if (existError == 'no'){
                    if (data.length > 2){
                        Element.after('<div class="invalid-feedback error-regex">'+data[2]+'</div>')
                    }else {
                        Element.after('<div class="invalid-feedback error-regex">'+defaultErrorMessage.regex +'</div>')
                    }
                }
            }
        }

    }
    /*add class to item to show error status*/
    if (hasAnyError){
        Element.removeClass('is-valid').addClass('is-invalid');
    }else {
        Element.addClass('is-valid').removeClass('is-invalid');
    }
}

/*cancel submit if validation (gt,gtInt,lt,ltInt,min,max) error and required*/
$('form').submit(function (e) {
    var item=$(this).find('div.error-gt,div.error-gtInt,div.error-lt,div.error-ltInt,div.error-min,div.error-max').prev('input[required]');
    if (item.length>0){
        e.preventDefault();
        item[0].focus();
    }
});

/*run function validate by attr
* <input type="text" data-validate="gt:33" data-patternType="gt" required>
*/
function validateByAttr() {
    var items=$('input[data-validate]');
    for (var i = 0; i <items.length ; i++) {
        $(items[i]).keyup(function () {
            validate($(this),$(this).attr('data-validate'),$(this).attr('data-patternType'));
        });
    }
}
