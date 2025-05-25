<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Expense Invoice</title>
</head>
<body>
    <div style="text-align:center;">
        <H3 style="text-align:center; margin:0;">Quick Phone Fix N More</H3>
        <p style="text-align:center; margin:0; font-size:14px;">Daily Expense Report</p>
        @if($request->from && $request->to)
            <p style="text-align:center; margin:0; font-size:12px;">From: {{$request->from}}</p>
            <p style="text-align:center; margin:0; font-size:12px;">To: {{$request->to}}</p>
        @endif
        <p style="text-align:center; margin:0; font-size:12px;">Date: {{date('Y-m-d')}}</p>
    </div>
    <div style="display: flex; justify-content: center; margin-top: 10px; overflow-x: auto;">
        <table style="margin: 0 auto; text-align: center; font-size:10px; border-collapse: collapse; border: 1px solid #000;">
            <thead>
                <tr role="row">
                    <th style="border: 1px solid #000;">#</th>
                    <th style="border: 1px solid #000;">Date</th>
                    <th style="border: 1px solid #000;">Purpose of Expense</th>
                    <th style="border: 1px solid #000;">Amount</th>
                    <th style="border: 1px solid #000;">Spend Method</th>
                    <th style="border: 1px solid #000;">Assign Person of work</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dailyExpense as $expense)
                <tr role="row" class="odd">
                    <td style="border: 1px solid #000;">{{$loop->index+1}}</td>
                    <td style="border: 1px solid #000;">{{$expense->date}}</td>
                    <td style="border: 1px solid #000;">{{$expense->purpose_of_expense}}</td>
                    <td style="border: 1px solid #000;">${{number_format($expense->amount, 2)}}</td>
                    <td style="border: 1px solid #000;">{{ucfirst($expense->spend_method)}}</td>
                    <td style="border: 1px solid #000;">{{$expense->assign_by}}</td>
                </tr>
                @endforeach
                <!-- Total Row -->
                <tr>
                    <td colspan="3" style="border: 1px solid #000; text-align: right; font-weight: bold;">Total:</td>
                    <td style="border: 1px solid #000; font-weight: bold;">${{ number_format($dailyExpense->sum('amount'), 2) }}</td>
                    <td colspan="2" style="border: 1px solid #000;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>