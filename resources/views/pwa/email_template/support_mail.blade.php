<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support</title>
</head>
<body>
    <table style="width: 100%; max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; border-collapse: collapse;">
        <tr>
            <td style="background-color: #f0f0f0; padding: 20px; text-align: center;">
                <h1>Help & Support</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <p>Hello ,</p>
                <p>Email : {{$user->email}}</p>
                <p>Subject : {{ $data['category'] ?? ''}}</p>
                <p>Issue : {{ $data['issue'] ?? ''}}</p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f0f0f0; padding: 20px; text-align: center;">
                <p>&copy; {{ date('Y') }} My Office. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
