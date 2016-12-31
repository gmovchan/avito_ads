$(document).ready(function () {
  $("#telephone_number_send").mask("+7(999) 999-9999", {
    completed: function () {
      if ($('#telephone_number_send').val() == '') {
        $('#help_block_for_telephone_number').hide('slow');
        $('#telephone_number_div').removeClass('has-success').removeClass('has-error');
      } else {
        $('#help_block_for_telephone_number').show('slow');
        check_on_repeat($('#telephone_number_send').val(), 'telephone_number');
      }
    }
  });

  $('#help_block_for_telephone_number').hide();
  $('#help_block_for_organization').hide();

  getTimeAjax();
  get_max_number();

  $('#link').on('input',function(e){
   get_link();
  })

  $('#organization_send').on('input',function(e){
   if ($(this).val() == '') {
     $('#help_block_for_organization').hide('slow');
     $('#organization_div').removeClass('has-success').removeClass('has-error');
   } else {
      $('#help_block_for_organization').show('slow');
     check_on_repeat($(this).val(), 'organization');
   }
  })

/*
  $('#telephone_number_send').on('input',function(e){
    if ($(this).val() == '') {
      $('#help_block_for_telephone_number').hide('slow');
      $('#telephone_number_div').removeClass('has-success').removeClass('has-error');
    } else {
      $('#help_block_for_telephone_number').show('slow');
      check_on_repeat($(this).val(), 'telephone_number');
    }
  })
*/
  //если цена не договрная, то переключатель делает активным поле для ввода цифр
  $('#radio_price_text').click('input',function(e){
     $('#price_text').prop('disabled', false);
  })

  $('#negotiated_price').click('input',function(e){
     $('#price_text').prop('disabled', true);
  })

  function get_link() {
    var str = $('#link').val();
    var start = str.lastIndexOf("_");
    var id = str.substring(start + 1);

    if (start < 0 || id == "") {

      if (str == '') {
        $('#help_block_for_id').text('Вставьте ссылку в поле ниже');
        $('#avito_id_div').removeClass('has-success').removeClass('has-error');
      } else {
        $('#help_block_for_id').text("ID в ссылке не найден");
        $('#avito_id_div').removeClass('has-success').addClass('has-error');
      }

      //изменет значение скрытого поля, чтобы можно было его отправить с посощью POST формы
      $('#id_for_send').val("");
    } else {
      $('#help_block_for_id').show("slow");
      $('#id_for_send').val(id);
      check_on_repeat(id, 'id_avito');

    }
  }

  function getTimeAjax() {
    $("#updatedTime").load("php/time.php");
  }

  /* функция отправляет данные из поля для ввода скрипту, который проверяет, есть ли
  уже такие данные в БД, в случае ошибки меняет подсказку к полю и его
  цвет на красный или в случае успеха на зеленый */
  function check_on_repeat(data, type) {

    $.ajax({
      url: "./php/check_on_repeat.php",
      cache: false,
      data: "data="+data+"&type="+type,
      dataType: "json",
      success: function (json) {

        var block,
            block_help,
            message_success,
            message_error;

        switch (type) {
          case 'id_avito':
            block = '#avito_id_div';
            block_help = '#help_block_for_id';
            message_success = 'Это новое объявление';
            message_error = 'Данное объявление уже добавлено под номером '+json.number;
            break;

          case 'organization':
            block = '#organization_div';
            block_help = '#help_block_for_organization';
            message_success = 'Новая организация';
            message_error = 'С этой организацией уже есть объявление под номером '
            +json.number;
            break;

          case 'telephone_number':
            block = '#telephone_number_div';
            block_help = '#help_block_for_telephone_number';
            message_success = 'Новый телефонный номер';
            message_error = 'С этим телефонным номером уже есть объявление под номером '
            +json.number;
            break;

          default:
            break;
        }

        if (json.repeat == "true") {
          console.log("json.repeat = true")
          $(block_help).text(message_error);
          $(block).removeClass('has-success').addClass('has-error');
        } else {
          $(block_help).text(message_success);
          $(block).removeClass('has-error').addClass('has-success');
        }
      }
    });
  }

  function get_max_number() {
    $.ajax({
      url: "./php/max_number.php",
      cache: false,
      dataType: "json",
      success: function (json) {
        $('#max_number').val(json.number);
      }
    });
  }

  function check_telephone_number() {

  }

})
