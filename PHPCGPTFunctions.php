const LIST_MODELS = 'https://api.openai.com/v1/models';
const CHAT_COMPLETION = 'https://api.openai.com/v1/chat/completions';
const EDITS = 'https://api.openai.com/v1/edits';

$models = [
     'gpt-3.5-turbo',     //Most capable model and optimized for chat at 1/10th the cost of text-davinci-003
     'gpt-3.5-turbo-0301',  //snapshot of turbo fro March 1st 2023. Will no longer receive updates
    'text-davinci-003',  //Can do any language task with better quality, longer output, and consistent instruction following. Also supports inserting completions within text
    'text-davinci-002',  //trained with supervised fine-tuning instead of reinforcement learning
    'code-davinci-002'  //optimized for code completion tasks
];

function send_prompt(string $url,string $request_type, array $request, string $key)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($request_type === "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    } else {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
    }


    $headers = array();
    $headers[] = 'Authorization: Bearer '.$key;
    if($request_type === "GET") {
        $headers[] = 'Openai-Organization: org-lopRoDOmIxbsKGkUZCz2hZAo';
    } else {
        $headers[] = 'Content-Type: application/json';
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

function say(string $what_to_say){
    return [
        "model" => "gpt-3.5-turbo",
        "messages" => [["role" => "user", "content" => $what_to_say]]
    ];
}

function edit_text(string $input, string $instruction, string $model){
    if(($model === "text-davinci-edit-001") || ($model === "code-davinci-edit-001")){
        return json_encode(['model' => $model, 'input' => $input, 'instruction' => $instruction]);
    }
}
