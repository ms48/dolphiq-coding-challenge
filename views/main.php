<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Dolphiq Coding Challenge</title></head>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <body>       
        <!-- Page content-->
        <div class="container">
            <!-- Error block -->
            <p class="error"></p>
            <!-- Inputs block -->
            <h2>Enter commands to send!</h2>
            <form>
                <textarea autofocus name="commands" id="commands"></textarea>
                <button id="send" type="submit">Send Now</button>
            </form>
            <!-- Results block -->
            <div id="result-wrapper" class="hidden">
                <h2>Results!</h2>
                <p class="result"></p>
            </div>
        </div>
        
        <!-- Scripts-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(function () {
                $("#send").click(function (e) {
                    e.preventDefault();
                    const commands =  $('#commands').val();
                    //validate
                    if(!commands) {
                        addError('No commands were found to send!');
                        return;
                    }
                    
                    //send data to the backend
                    $.post('/send', {commands: $('#commands').val()})
                        .done(function (res) {
                            showResult(res.data.join("\r\n")); //convert array to string
                        })
                        .fail(function (error, textStatus, errorThrown) {
                            addError($.parseJSON(error.responseText).message);
                        });
                });
                function addError(error) {
                    $('.error').html(error);
                    hideResult();
                }

                function hideResult() {
                    $('#result-wrapper').addClass('hidden');
                    $('.result').html('');
                }

                function showResult(result) {
                    console.log(result)
                    $('#result-wrapper').removeClass('hidden');
                    $('.result').html(`<pre>${result}</pre>`);
                    $('.error').html('');
                }
            });
        </script>
    </body>
</html>