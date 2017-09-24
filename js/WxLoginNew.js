/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


!
        function (a, b, c) {
            function d(a) {
                var c = "default";
                a.self_redirect === !0 ? c = "true" : a.self_redirect === !1 && (c = "false");
                var d = b.createElement("iframe"),
                        e = "https://open.weixin.qq.com/connect/qrconnect?appid=" + a.appid + "&scope=" + a.scope + "&redirect_uri=" + a.redirect_uri + "&state=" + a.state + "&login_type=jssdk&self_redirect=" + c;
                e += a.style ? "&style=" + a.style : "",
                        e += a.href ? "&href=" + a.href : "",
                        d.src = e,
                        d.frameBorder = "0",
                        d.allowTransparency = "true",
                        d.scrolling = "no",
                        d.width = "200px",
                        d.height = "300px";
                var f = b.getElementById(a.id);
                f.innerHTML = "",
                        f.appendChild(d)
            }
            a.WxLogin = d
        }(window, document);