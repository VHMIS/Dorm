var sitePath = '/kytucxa/';

var closeModal = function(modal) {
    $('#lean_overlay').fadeOut(1, function () {
        $('#' + modal).hide();
        $(this).remove();
    });
}

// Control Day Nha
var insertAreaData = function(id) {
    $('#update_area_name').val(areaData['a_' + id].name);
    $('#update_area_id').val(id);
}

var updateArea = function() {
    var id = $('#update_area_id').val();
    var name = $('#update_area_name').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'cosovatchat/daynha/update',
        data: 'id=' + id + '&name=' + name,
        success: function(msg) {
            if(msg=='ok') {
                areaData['a_' + id].name = name;
                $('#a_' + id + ' .name').text(name);
                closeModal('update_area');
            } else {
                alert(msg);
            }
        }
    });
}

var newArea = function() {
    var name = $('#new_area_name').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'cosovatchat/daynha/new',
        data: 'name=' + name,
        success: function(msg) {
            if(msg > 0) {
                areaData['a_' + msg] = {
                    'name': name
                };

                var newA = $('<li id="a_' + msg + '">' +
                    '<div class="name">' + name + '</div>' +
                    '<div class="edit"><a id="a_' + msg + '_button_edit" href="#update_area" onclick="insertAreaData(\'' + msg + '\')">Edit</a></div>' +
                    '<div class="delete"><!--<a href="#" onclick="">Delete</a>--></div>' +
                '</li>');

                newA.appendTo('#list_area');

                $('#a_' + msg + '_button_edit').leanModal();

                closeModal('new_area');
            } else {
                alert(msg);
            }
        }
    });
}

// Control Phong
var insertRoomData = function(id) {
    $('#update_room_gender').val(roomData['r_' + id].sex);
    $('#update_room_type').val(roomData['r_' + id].type);
    $('#update_room_area').val(roomData['r_' + id].area);
    $('#update_room_name').val(roomData['r_' + id].name);
    $('#update_room_id').val(id);
}

var updateRoom = function() {
    var id = $('#update_room_id').val();
    var name = $('#update_room_name').val();
    var area = $('#update_room_area').val();
    var type = $('#update_room_type').val();
    var sex = $('#update_room_gender').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'cosovatchat/phong/update',
        data: 'id=' + id + '&name=' + name + '&area=' + area + '&type=' + type + '&sex=' + sex,
        success: function(msg) {
            if(msg=='ok') {
                roomData['r_' + id].name = name;
                if(roomData['r_' + id].area != area)
                {
                    $('#r_' + id).appendTo('#tabs_room_content_area_' + area);
                }
                roomData['r_' + id].area = area;
                roomData['r_' + id].type = type;
                roomData['r_' + id].sex = sex;
                $('#r_' + id + ' .name a').text(name);
                $('#r_' + id + ' .gender').text((sex == 2 ? '-' : '+'));
                $('#r_' + id + ' .maxium').text(roomTypeData['rt_' + type].maxs);
                closeModal('update_room');
            } else {
                alert(msg);
            }
        }
    });
}

var newRoom = function() {
    var name = $('#new_room_name').val();
    var area = $('#new_room_area').val();
    var type = $('#new_room_type').val();
    var sex = $('#new_room_gender').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'cosovatchat/phong/new',
        data: 'name=' + name + '&area=' + area + '&type=' + type + '&sex=' + sex,
        success: function(msg) {
            if(msg > 0) {
                roomData['r_' + msg] = {
                    'name': name,
                    'area': area,
                    'type': type,
                    'sex': sex
                };

                var newR = $('<li id="r_' + msg + '"><div class="name"><a id="r_' + msg + '_button_edit" href="#update_room" onclick="insertRoomData(\'' + msg + '\')">' + name + '</a></div><div class="gender">' + (sex == 2 ? '-' : '+') + '</div><div class="maxium">' + roomTypeData['rt_' + type].maxs + '</div></li>');

                newR.appendTo('#tabs_room_content_area_' + area);

                $('#r_' + msg + '_button_edit').leanModal();

                closeModal('new_room');
            } else {
                alert(msg);
            }
        }
    });
}

// Control Loai Phong
var insertRoomTypeData = function(id) {
    $('#update_room_type_name').val(roomTypeData['rt_' + id].name);
    $('#update_room_type_max_students').val(roomTypeData['rt_' + id].maxs);
    $('#update_room_type_fee_day').val(roomTypeData['rt_' + id].feed);
    $('#update_room_type_fee_month').val(roomTypeData['rt_' + id].feem);
    $('#update_room_type_id').val(id);
}

var updateRoomType = function() {
    var id = $('#update_room_type_id').val();
    var name = $('#update_room_type_name').val();
    var maxs = $('#update_room_type_max_students').val();
    var feed = $('#update_room_type_fee_day').val();
    var feem = $('#update_room_type_fee_month').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'thietlap/danhmucloaiphong/update',
        data: 'id=' + id + '&name=' + name + '&maxs=' + maxs + '&feed=' + feed + '&feem=' + feem,
        success: function(msg) {
            if(msg=='ok') {
                roomTypeData['rt_' + id].name = name;
                roomTypeData['rt_' + id].maxs = maxs;
                roomTypeData['rt_' + id].feed = feed;
                roomTypeData['rt_' + id].feem = feem;
                $('#rt_' + id + ' .name').text(name);
                $('#rt_' + id + ' .max_students').text(maxs);
                $('#rt_' + id + ' .fee_day').text(feed + ' VND');
                $('#rt_' + id + ' .fee_month').text(feem + ' VND');
                closeModal('update_room_type');
            } else {
                alert(msg);
            }
        }
    });
}

var newRoomType = function() {
    var name = $('#new_room_type_name').val();
    var maxs = $('#new_room_type_max_students').val();
    var feed = $('#new_room_type_fee_day').val();
    var feem = $('#new_room_type_fee_month').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'thietlap/danhmucloaiphong/new',
        data: 'name=' + name + '&maxs=' + maxs + '&feed=' + feed + '&feem=' + feem,
        success: function(msg) {
            if(msg > 0) {
                roomTypeData['rt_' + msg] = {
                    'name': name,
                    'maxs': maxs,
                    'feed': feed,
                    'feem': feem
                };

                var newRT = $('<li id="rt_' + msg + '">' +
                    '<div class="name">' + name + '</div>' +
                    '<div class="max_students">' + maxs + '</div>' +
                    '<div class="fee_day">' + feed + ' VND</div>' +
                    '<div class="fee_month">' + feem + ' VND</div>' +
                    '<div class="edit"><a id="rt_' + msg + '_button_edit" href="#update_room_type" onclick="insertRoomTypeData(\'' + msg + '\')">Edit</a></div>' +
                    '<div class="delete"><!--<a href="#" onclick="">Delete</a>--></div>' +
                '</li>');

                newRT.appendTo('#list_room_type');

                $('#rt_' + msg + '_button_edit').leanModal();

                closeModal('new_room_type');
            } else {
                alert(msg);
            }
        }
    });
}

var newRoomType = function() {
    var name = $('#new_room_type_name').val();
    var maxs = $('#new_room_type_max_students').val();
    var feed = $('#new_room_type_fee_day').val();
    var feem = $('#new_room_type_fee_month').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'thietlap/danhmucloaiphong/new',
        data: 'name=' + name + '&maxs=' + maxs + '&feed=' + feed + '&feem=' + feem,
        success: function(msg) {
            if(msg > 0) {
                roomTypeData['rt_' + msg] = {
                    'name': name,
                    'maxs': maxs,
                    'feed': feed,
                    'feem': feem
                };

                var newRT = $('<li id="rt_' + msg + '">' +
                    '<div class="name">' + name + '</div>' +
                    '<div class="max_students">' + maxs + '</div>' +
                    '<div class="fee_day">' + feed + ' VND</div>' +
                    '<div class="fee_month">' + feem + ' VND</div>' +
                    '<div class="edit"><a id="rt_' + msg + '_button_edit" href="#update_room_type" onclick="insertRoomTypeData(\'' + msg + '\')">Edit</a></div>' +
                    '<div class="delete"><!--<a href="#" onclick="">Delete</a>--></div>' +
                '</li>');

                newRT.appendTo('#list_room_type');

                $('#rt_' + msg + '_button_edit').leanModal();

                closeModal('new_room_type');
            } else {
                alert(msg);
            }
        }
    });
}

// Control Thue Phong
var studentGoRoom = function(roomId)
{
    if(selected_student.id == 0)
	{
	    alert('Chọn 1 sinh viên');
	    return;
	}

	var date_in = $('#date_in').val();
	var date_out = $('#date_out').val();

	if(date_in == '' || date_out == '')
	{
	    alert('Chọn ngày vào và ngày ra (dự kiến)');
	    return;
	}

	if(roomData['r_' + roomId].sex != selected_student.sex)
	{
	    alert('Vui lòng chọn phòng dành cho ' + (selected_student.sex == 2 ? 'nữ (dấu -)' : 'nam (dấu +)'));
	    return;
	}

	if(roomData['r_' + roomId].current_student == roomTypeData['rt_' + roomData['r_' + roomId].type].maxs)
	{
	    alert('Phòng đầy');
	    return;
	}

	$.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/sinhvienthuephong',
        data: 'sid=' + selected_student.id + '&rid=' + roomId + '&date_in=' + date_in + '&date_out=' + date_out,
        success: function(msg) {
            if(msg > 0) {
                var SIR = $('<li id="student_in_room_' + msg + '">' +
                    '<div class="name">' + selected_student.name + '</div>' +
                    '<div class="room" id="sir_id_' + roomId + '">' + roomData['r_' + roomId].name + '</div>' +
                    '<div class="gender">' + (selected_student.sex == 2 ? 'Nữ' : 'Nam') + '</div>' +
                    '<div class="fee">&nbsp;</div>' +
                    '<div class="payedfee">&nbsp;</div>' +
                    '<div class="debt">&nbsp;</div>' +
                    '<div class="money"><a id="student_in_room_' + msg + '_button" href="#studen_roomfee" onclick="studentFee(' + msg + ')">Tien</a></div>' +
                    '<div class="delete"><a href="#" onclick="studentCancelRoom(' + msg + ');return false;">del</a></div>' +
                '</li>');
                $('#list_student_in_room').prepend(SIR);
                roomData['r_' + roomId].current_student++;
                $('#r_' + roomId + ' .maxium').text(roomData['r_' + roomId].current_student + " / " + roomTypeData['rt_' + roomData['r_' + roomId].type].maxs);

                selected_student.sex = 0;
                selected_student.name = '';
                selected_student.code = '';
                selected_student.id = 0;

                $('#student_info').text('Nhập mã sinh viên ở ô trên để lấy thông tin');
                $('#vaolaiphongcu').css('display', 'none');

                $('#student_in_room_' + msg + '_button').leanModal();
            }
            else {
                alert(msg);
            }
        }
    });
}

var studentFee = function(id)
{
    var name = $("#student_in_room_" + id + " .name").text();
    var room = $("#student_in_room_" + id + " .room").text();

    $('#feeinfo_name').text(name);
    $('#feeinfo_desc').text(room);

    $.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/trogiup/thongtinphi',
        data: 'id=' + id,
        success: function(msg) {
            $('#feeinfo_main').html(msg);
        }
    });
}

var studentRemoveFee = function(id)
{
    $.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/huytraphi',
        data: 'id=' + id,
        success: function(msg) {
            if(msg == '0') {
                var refund = $("#bill_list_" + id + " .money").text();
                payment_fee.debt += parseInt(refund);
                payment_fee.payed_fee -= parseInt(refund);

                $('#info_money').text(payment_fee.total_fee + " - " + payment_fee.payed_fee + " = " + payment_fee.debt);
                $('#money_will_pay').val(payment_fee.debt);
                $("#bill_list_" + id).remove();
            } else {
                alert(msg);
            }
        }
    });
}

var studentChangeSex = function()
{
    if(selected_student.id == 0)
	{
	    alert('Chọn 1 sinh viên');
	    return;
	}

	$.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/trogiup/doigioitinh',
        data: 'id=' + selected_student.id,
        success: function(msg) {
            if(msg == '0') {
                selected_student.sex = selected_student.sex == 2 ? 1 : 2;
                $('#student_info').text(selected_student.code + " - " + selected_student.name + " (" + (selected_student.sex == 2 ? 'Nữ' : 'Nam') + ")");
            } else {
                alert(msg);
            }
        }
    });
}

var studentCancelRoom = function(id)
{
    var com = confirm('Xóa sinh viên này! CHÚ Ý, dữ liệu sẽ mất!');
    if(!com) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/huythuephong',
        data: 'id=' + id,
        success: function(msg) {
            if(msg == '0') {
                var roomId = $("#student_in_room_" + id + " .room").attr('id');
                roomId = roomId.replace('sir_id_', '');
                roomData['r_' + roomId].current_student--;
                $('#r_' + roomId + ' .maxium').text(roomData['r_' + roomId].current_student + " / " + roomTypeData['rt_' + roomData['r_' + roomId].type].maxs);
                $("#student_in_room_" + id).remove();
            } else {
                alert(msg);
            }
        }
    });
}

var studentOutRoom = function(id)
{
    var com = confirm('Cho phép sinh viên ra khỏi túc xá');
    if(!com) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/rakhoiphong',
        data: 'id=' + id,
        success: function(msg) {
            if(msg == '0') {
                $("#student_in_room_" + id).remove();
            } else {
                alert(msg);
            }
        }
    });
}

var studentPayFee = function()
{
    if(payment_fee.id <= 0)
    {
        alert('No info');
        return false;
    }

    var fee = $('#money_will_pay').val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/traphi',
        data: 'id=' + payment_fee.id + '&fee=' + fee,
        success: function(msg) {
            if(msg > 0) {
                var SP = $('<li id="bill_list_' + msg + '">' +
                    '<div class="billno">#' + msg + '</div>' +
                    '<div class="date">Mới nộp</div>' +
                    '<div class="money">' + fee + '</div>' +
                    '<div class="print"><a href="' + sitePath + 'noitru/thuephong/inhoadon/' + msg + '">bill</a></div>' +
                    '<div class="delete"><a href="#" onclick="studentRemoveFee(' + msg + ');return false;">del</a></div>' +
                '</li>');
                $('#bill_list').prepend(SP);
                payment_fee.payed_fee += parseInt(fee);
                payment_fee.debt = payment_fee.total_fee - payment_fee.payed_fee

                $('#info_money').text(payment_fee.total_fee + " - " + payment_fee.payed_fee + " = " + payment_fee.debt);
                $('#money_will_pay').val(payment_fee.debt);
            }
            else {
                alert(msg);
            }
        }
    });
}

var findStudent = function() {
    var code = $('#student_code').val();

    $('#vaolaiphongcu').css('display', 'none');

    $.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/trogiup/timsinhvien',
        data: 'code=' + code,
        success: function(msg) {
            if(msg < 0) {
                alert(msg);
            }
            else {
                var info = msg.split("|||", 4);
                selected_student.sex = info[2];
                selected_student.name = info[0];
                selected_student.code = code;
                selected_student.id = info[1];
                selected_student.room_id = info[3];

                if(selected_student.room_id != 0) {
                    $('#vaolaiphongcu').html('<a class="button" href="#" onclick="studentGoRoom(' + selected_student.room_id + ');return false;">Vào lại phòng cũ ' + roomData['r_' + selected_student.room_id].name + '</a>');
                    $('#vaolaiphongcu').css('display', 'block');
                }

                $('#student_info').text(selected_student.code + " - " + selected_student.name + " (" + (selected_student.sex == 2 ? 'Nữ' : 'Nam') + ")");
                $('#student_code').val('');
            }
        }
    });
}

// Tabs Control
var tabsControl = Array();

// Data
var roomTypeData = Array();
var areaData = Array();
var roomData = Array();

// SO DO
var sodo_hientai = "sinhvieno";
var sodo_hientai_s = "";
var viewRoomMap = function(type, sub_type) {
    if(sub_type != '')
    {
        if(type == sodo_hientai && sub_type == sodo_hientai_s) return true;
    }
    else if(type == sodo_hientai) return true;

    $.each(roomInfo, function(id, value) {
        if(roomTypeData['rt_' + value.type].maxs == 0)
        {
            return true;
        }

        if(type == 'chocondu') {
            $('#map_title').text('Sơ đồ KTX : Phòng Còn Chổ Trống');
            if(value.current_student == roomTypeData['rt_' + value.type].maxs) {
                $('#' + id + ' .maxium').text('');
            }
            else {
                $('#' + id + ' .maxium').text(roomTypeData['rt_' + value.type].maxs - value.current_student);
            }

            //Thay
            if(sodo_hientai == "sinhvienotheokhoa")
            {
                $('#' + id + ' .maxium').removeClass('hidden');
                $('#' + id + ' .gender').removeClass('hidden');
                $('#' + id + ' .info').addClass('hidden');
            }
        }
        else if(type == 'sinhvieno') {
            $('#map_title').text('Sơ đồ KTX : Phòng và Sinh Viên')
            $('#' + id + ' .maxium').text(value.current_student + ' / ' + roomTypeData['rt_' + value.type].maxs);

            //Thay
            if(sodo_hientai == "sinhvienotheokhoa")
            {
                $('#' + id + ' .maxium').removeClass('hidden');
                $('#' + id + ' .gender').removeClass('hidden');
                $('#' + id + ' .info').addClass('hidden');
            }
        }
        else if(type == 'sinhvienotheokhoa') {
            $('#map_title').text('Sơ đồ KTX : Phòng và Sinh Viên (Khóa)')
            var info = value.sex == 1 ? '+ ' : '- ';
            $.each(value.schoolyear, function(k,t){
                info += k.replace('k_', '') + '<sup>' + t + '</sup>';
            })
            $('#' + id + ' .info').html(info);

            $('#' + id + ' .maxium').addClass('hidden');
            $('#' + id + ' .gender').addClass('hidden');
            $('#' + id + ' .info').removeClass('hidden');
        }
        else if(type == 'sinhvienokhoa') {
            $('#map_title').text('Sơ đồ KTX : Phòng và Sinh Viên Khóa ' + sub_type)
            if(value.schoolyear['k_' + sub_type] == null) $('#' + id + ' .maxium').text('');
            else $('#' + id + ' .maxium').text(value.schoolyear['k_' + sub_type])

            if(sodo_hientai == "sinhvienotheokhoa")
            {
                $('#' + id + ' .maxium').removeClass('hidden');
                $('#' + id + ' .gender').removeClass('hidden');
                $('#' + id + ' .info').addClass('hidden');
            }
        }
    });
    sodo_hientai = type;
    sodo_hientai_s = sub_type;
}



// Simple tabs
var simpleTabs = function(id) {
    $('.' + id).click(function() {
        if($(this).hasClass('active')) return;
        var oldTab = tabsControl[id + '_current'].replace(id + '_content_', '');
        var newTab = this.id.replace(id + '_', '');
        $('#' + id + '_content_' + newTab).removeClass('hidden');
        $('#' + id + '_content_' + oldTab).addClass('hidden');
        $('#' + id + '_' + oldTab).removeClass('active');
        $('#' + id + '_' + newTab).addClass('active');
        tabsControl[id + '_current'] = id + '_content_' + newTab;
    })
}

var studentMove = function(id)
{
    var name = $("#student_in_room_" + id + " .name").text();
    var room = $("#student_in_room_" + id + " .room").text();
    var roomId = $("#student_in_room_" + id + " .room").attr('id');
    roomId = roomId.replace('sir_id_', '');

    $('#roomchange_name').text(name);
    $('#roomchange_desc').text(room);

    var list = "";
    for (var index in roomData) {
        var rid = index.replace('r_', '');
        if(rid == roomId) continue;
        if(roomData["r_" + rid].sex != roomData["r_" + roomId].sex) continue;
        if(roomData["r_" + rid].type != roomData["r_" + roomId].type) continue;
        list += "<option value=\"" + index.replace('r_', '') + "\">" + roomData[index].name + "</option>";
    }

    $('#sir_id_change').val(id);
    $('#room_id_change').html(list);
}

var studentMoveRoom = function()
{
    var sir = $("#sir_id_change").val();
    var orid = $("#student_in_room_" + sir + " .room").attr('id');
    var nrid = $("#room_id_change").val();

    $.ajax({
        type: 'POST',
        url: sitePath + 'noitru/thuephong/chuyenphong',
        data: 'sid=' + sir + '&nrid=' + nrid,
        success: function(msg) {
            if(msg == 1)
            {
                var siroom = $("#student_in_room_" + sir);
                // sửa lại id room
                var html = siroom.html();
                siroom.html(html.replace(orid, 'sir_id_' + nrid));
                // remove last child
                $("#list_students_in_room_" + nrid + " li").filter(":last").remove();
                $("#list_students_in_room_" + nrid ).prepend(siroom);
                orid = orid.replace('sir_id_', '')
                // add new space for old room
                $("#list_students_in_room_" + orid ).append($('<li><div class="num">-</div><div class="name">&nbsp;</div><div></div><div></div><div></div></li>'));

                // move room again
                studentMove(sir);
            }
            else
            {
                alert(msg);
            }
        }
    });
}

$(function() {


    //Modal
    $("a[rel*=leanModal]").leanModal();

    // Menu
    $('ul.menu li.sub_menu').hover(
    	function() {
    	    $(this).addClass('hover');
    	    $(this).find('ul.sub_menu').show();
    	},
    	function() {
    	    $(this).removeClass('hover');
    	    $(this).find('ul.sub_menu').hide();
    	}
    );

    // Tabs
    simpleTabs('tabs_room');
    simpleTabs('tabs_use_room');
    simpleTabs('tabs_stat');

    // Date picker
    $("#date_in, #date_out").datepicker({
        dateFormat: 'dd/mm/yy'
    });

    // Dropable
    $('ul.room_info_list li').droppable({
        drop: function( event, ui ) {
            var id = this.id.replace('r_', '');
        	studentGoRoom(id);
        },
        over: function(event, ui) {
            ui.helper.text(roomData['r_' + this.id.replace('r_', '')]['name']);
        },
        out: function(event, ui) {
            ui.helper.text('Phòng');
        }
    });
    $('#student_info').draggable({
        cursor: 'move',
        opacity: 0.7,
        cursorAt: {cursor: "move", top: 0, left: 4},
        helper: function(event) {
        	return $("<div class='box_info' id='helper_choose_room'>Phòng</div>" );
        }
    });

    // Side bar ontop
    $('#sidebar_add_room').stickyScroll({ container: '.main' });
});