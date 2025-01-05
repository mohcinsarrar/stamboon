<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Template</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background-color: transparent;
      
    }
    .header {
      text-align: center;
      padding: 20px 0;
      background-color: transparent;
    }
    .header img {
      max-width: 150px;
    }
    .content {
        background-color: #ffffff;
      padding: 20px;
      font-size: 16px;
      line-height: 1.5;
      color: #333333;
    }
    .footer {
        border: 1px solid #dddddd;
        
        text-align: center;
        padding: 10px 0;
        font-size: 12px;
        color: #777777;
    }
    .footer a{
        text-decoration: none;
        color: #777777 !important;
    }
    @media screen and (max-width: 600px) {
      .email-container {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <table class="email-container" cellpadding="0" cellspacing="0" width="100%">
    <!-- Header -->
    <tr>
      <td class="header">
        <a href="{{$website_url}}">
          <img src="{{$logo}}" alt="Company Logo">
        </a>
      </td>
    </tr>
    <!-- Content -->
    <tr>
      <td class="content">
        <!-- Replace with your content -->
        <h4>{{$title}}</h4>
        <p>
          Hello {{$user_fullname}},
        </p>
        <p>
          {!!$content!!}
        </p>
        <p>
          Best Regards,<br>
            {{$company_name}}
        </p>
      </td>
    </tr>
    <!-- Footer -->
    <tr>
      <td class="footer" style="background-color: {{$footer_color}};">
        <p>© {{$current_year}} {{$company_name}}. All Rights Reserved.</p>
        <p>
          Contact us: <a href="mailto:{{$contact_email}}">{{$contact_email}}</a> 
        </p>
      </td>
    </tr>
  </table>
</body>
</html>
