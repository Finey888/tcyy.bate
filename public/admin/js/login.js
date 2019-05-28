layui.use(['jquery', 'layer'], function () {
    var $ = layui.jquery;
    var layer = layui.layer;
    $('#loginsub').click(function () {
        loginuser = $('#loginuser').val();
        loginpw = $('#loginpw').val();
        loginvif = $('#loginvif').val();
       
        if (loginuser == "") {
            layer.msg("用户名不能为空");
            return false;
        }
        if (loginpw == "") {
            layer.msg("密码不能为空");
            return false;
        }
        if (loginvif == "") {
            layer.msg("验证码不能为空");
            return false;
        }
        data = {
            loginuser: loginuser,
            loginpw: loginpw,
            loginvif: loginvif
        }
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/index/login/loginVerify",
            data: data,
            success: function (t) {
                if (parseInt(t.status) == 0) {
                    layer.msg("登陆成功");
                    setTimeout(function () {
                       window.location.href = '/';
                    }, 1000)
                } else {
                    layer.msg(t.msg);
                    setTimeout(function () {
                        window.location.href = '/';
                    }, 1000)
                }
            }
        })
    });
    $(".viftyimg").click(function () {
        $(this).attr("src", "/index/login/verifyImg")
    });
})