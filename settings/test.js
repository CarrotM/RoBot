function AddElement(line) {
    console.log(line);

    if ($('#row' + (line + 1)).val() == null) {
        $('#steptostep').append('<div class="row border-bottom" id="row' + (line + 1) + '"></div>');
    }


    $('#row' + (line + 1)).append(
        '<div class="col text-center">' +
        '<button type="button" class="btn btn-primary rounded-pill addbtn">' +
        '<i class="now-ui-icons ui-1_settings-gear-63"></i> Новый элемент' +
        '<span class="position-absolute start-50 translate-middle bg-danger border border-light rounded-circle addbtnspan" onclick="AddElement(' + (line + 1) + ');" style="top: 85% !important;height: 20px; width: 20px; padding: 0px; left: 50% !important; padding-top: 1px;">' +
        '<i class="now-ui-icons ui-1_simple-add"></i>' +
        '</span>' +
        '</button>' +
        '</div>');

}

function SaveSettings() {
    var mode = 0;
    if($('#aitab').hasClass('active')) mode = 1;
    var settings = [
        $('#settings-hitext').val(),
        $('#settings-errortext').val(),
        mode
    ];
    
    var $input = $("#DialogflowFile");
    var fd = new FormData;

    fd.append('json', $input.prop('files')[0]);
    fd.append('method', 'AddKey');

    $.ajax({
        url: document.location.href,
        data: fd,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function (data) {
            if(data == 1)
            {
                demo.showNotification('bottom', 'right', 'Ключ Dialogflow загружен', 'info', 3000);
            }
            else if(data == 0){}
            else if(data == "notaccept")
            {
                demo.showNotification('bottom', 'right', 'Неверный файл ключа Dialogflow', 'danger', 3000);
            }
            else
            {
                demo.showNotification('bottom', 'right', 'Ключ Dialogflow не был загружен', 'danger', 3000);
            }
        }
    });
    $.ajax({
        url: document.location.href,
        method: 'post',
        data: {
            method: 'SaveSettings',
            hitext: settings[0],
            errortext: settings[1],
            mode: settings[2]
        },
        success: function (data) {
            demo.showNotification('bottom', 'right', 'Настройки сохранены', 'info', 3000);
        }
    });
    //console.log(settings);
}

$(document).ready(function () {
    $('#settings-save').click(function () { SaveSettings(); });
});



