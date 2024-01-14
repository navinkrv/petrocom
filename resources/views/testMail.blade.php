<!DOCTYPE html>
<html>

<head>
    <title>AllPHPTricks.com</title>
</head>

<body>
    <h1>{{ $testMailData['title'] }}</h1>
    <!-- <div>{{ $testMailData['body'] }}</div> -->
    <div>
        <table>
            <tbody>
                <tr>
                    <td>name</td>
                    <td>{{$testMailData['body']->name}}</td>
                </tr>
                <tr>
                    <td>email</td>
                    <td>{{$testMailData['body']->email}}</td>
                </tr>
                <tr>
                    <td>phone</td>
                    <td>{{$testMailData['body']->phone}}</td>
                </tr>
                <tr>
                    <td>Frieght type</td>
                    <td>{{$testMailData['body']->frieght_type}}</td>
                </tr>
                <tr>
                    <td>multidrop</td>
                    <td>{{$testMailData['body']->multidrop}}</td>
                </tr>
                <tr>
                    <td>Job Location Info</td>
                    <td>{{$testMailData['body']->job_location_data}}</td>
                </tr>
                <tr>
                    <td>length</td>
                    <td>{{$testMailData['body']->length}}</td>
                </tr>
                <tr>
                    <td>breadth</td>
                    <td>{{$testMailData['body']->breadth}}</td>
                </tr>
                <tr>
                    <td>height</td>
                    <td>{{$testMailData['body']->height}}</td>
                </tr>
                <tr>
                    <td>weight</td>
                    <td>{{$testMailData['body']->weight}}</td>
                </tr>
                <tr>
                    <td>message</td>
                    <td>{{$testMailData['body']->message}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>