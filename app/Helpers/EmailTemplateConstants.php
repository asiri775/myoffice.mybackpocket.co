<?php

namespace App\Helpers;


class EmailTemplateConstants
{

    //follow same structure
    // first project name, then three underscore, then user type, then two underscores, then type of template
    // for example:- MYOFFICE___USER__SIGNUP, myoffice is project, user is role and signup is type 

    const SIMPLE_NOTIFICATION = 'MYOFFICE___SIMPLE_NOTIFICATION';

    //authentications
    const USER__SIGNUP = 'MYOFFICE___USER__SIGNUP';
    const USER__ACTIVATION_EMAIL = 'MYOFFICE___USER__ACTIVATION_EMAIL';
    const USER__WELCOME_EMAIL = 'MYOFFICE___USER__WELCOME_EMAIL';
    const ADMIN__USER_SIGNUP = 'MYOFFICE___ADMIN__USER_SIGNUP';
    const HOST__SIGNUP = 'MYOFFICE___HOST__SIGNUP';
    const HOST__WELCOME_EMAIL = 'MYOFFICE___HOST__WELCOME_EMAIL';
    const ADMIN__HOST_SIGNUP = 'MYOFFICE___ADMIN__HOST_SIGNUP';
    const USER__FORGOT_PASSWORD = 'MYOFFICE___USER__FORGOT_PASSWORD';

    //bookings
    const USER__BOOKING_REQUEST = 'MYOFFICE___USER__BOOKING_REQUEST';
    const USER__BOOKING_DECLINED = 'MYOFFICE___USER__BOOKING_DECLINED';
    const USER__BOOKING_CONFIRMED = 'MYOFFICE___USER__BOOKING_CONFIRMED';
    const USER__BOOKING_UPDATES = 'MYOFFICE___USER__BOOKING_UPDATES';
    const USER__BOOKING_COMPLETED = 'MYOFFICE___USER__BOOKING_COMPLETED';
    const USER__BOOKING_CANCELLED = 'MYOFFICE___USER__BOOKING_CANCELLED';
    const USER__BOOKING_CHECKED_OUT = 'MYOFFICE___USER__BOOKING_CHECKED_OUT';
    
    const HOST__BOOKING_REQUEST = 'MYOFFICE___HOST__BOOKING_REQUEST';
    const HOST__BOOKING_DECLINED = 'MYOFFICE___HOST__BOOKING_DECLINED';
    const HOST__BOOKING_CONFIRMED = 'MYOFFICE___HOST__BOOKING_CONFIRMED';
    const HOST__BOOKING_UPDATES = 'MYOFFICE___HOST__BOOKING_UPDATES';
    const HOST__BOOKING_COMPLETED = 'MYOFFICE___HOST__BOOKING_COMPLETED';
    const HOST__BOOKING_CHECKOUT_REMINDER = 'MYOFFICE___HOST__BOOKING_CHECKOUT_REMINDER';

    //contact emails
    const HOST__CONTACT_BOOKING_DETAILS = 'MYOFFICE___HOST__CONTACT_BOOKING_DETAILS';
    const HOST__CONTACT_LISTING_DETAILS = 'MYOFFICE___HOST__CONTACT_LISTING_DETAILS';

    //withdrawal
    const USER__WITHDRAWAL_REQUEST = 'MYOFFICE___USER__WITHDRAWAL_REQUEST';
    const USER__WITHDRAWAL_COMPLETED = 'MYOFFICE___USER__WITHDRAWAL_COMPLETED';

    //credit purchases
    const ADMIN__CREDIT_PURCHASE = 'MYOFFICE___ADMIN__CREDIT_PURCHASE';
    const HOST__CREDIT_PURCHASE_CONFIRMATION = 'MYOFFICE___HOST__CREDIT_PURCHASE_CONFIRMATION';
    const ADMIN__WITHDRAWAL_REQUEST_RECEIVED = 'MYOFFICE___ADMIN__WITHDRAWAL_REQUEST_RECEIVED';

}
