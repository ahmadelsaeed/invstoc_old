
// Start Facebook API
window.fbAsyncInit = function () {
    FB.init({
        appId: '332446037092408',
        xfbml: true,
        version: 'v2.7'
    });


};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// Only works after `FB.init` is called

function myFacebookLogin() {

    FB.login(function () {

        var base_url2 = $(".url_class").val()+"/";
        var base_url = base_url2 + "/public_html/";
        var _token = $(".csrf_input_class").val();

        FB.api('/me',
            {fields: "id,picture,email,first_name,gender,name"},
            function (response) {
                console.log(base_url2 + "api_login/general_api_login");
                console.log('API response', response);

                if (typeof (response.email) != "undefined" && response.email != "") {

                    $.ajax({
                        url: base_url2 + "api_login/general_api_login",
                        type: 'POST',
                        data: {'_token': _token, 'name': response.name, 'email': response.email,'gender':response.gender, 'provider': 'facebook'},
                        success: function (data) {
                            var return_data = JSON.parse(data);
                            window.location = return_data.url;
                        }
                    });

                }

            }
        );

    }, {scope: 'email'});

// End Facebook API




}/**
 * Created by ahmed on 8/29/2016.
 */
