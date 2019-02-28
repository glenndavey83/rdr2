/**
 * Created by mirzaartbees on 15.6.2017.
 *
 * @version  6.1.0 Rename vip_user into mk_atp_user.
 */
if(typeof(mk_atp_user) != "undefined" && mk_atp_user !== null) {
    //Set your APP_ID
    var APP_ID = mk_atp_user.APP_ID;
    var user_email = mk_atp_user.user_email;
    var user_name = mk_atp_user.user_name;
    var user_id = mk_atp_user.user_id;
    var user_hash = mk_atp_user.user_hash;

    window.intercomSettings = {
        app_id: APP_ID,
        name: user_name, // Full name.
        email: user_email, // Email address.
        user_id: user_id, // user_id.
        user_hash: user_hash
    };
    (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function")
    {ic('reattach_activator');ic('update',intercomSettings);}else
    {var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args)
    {i.q.push(args)};w.Intercom=i;function l()
    {var s=d.createElement('script');s.type='text/javascript';s.async=true;
        s.src='https://widget.intercom.io/widget/' + APP_ID;
        var x=d.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}
    else{w.addEventListener('load',l,false);}}})();
}


