<?php

                        //
                        // OpenAI のアカウントを作成 ( https://beta.openai.com/signup )
                        // API Key を発行 ( https://beta.openai.com/account/api-keys )
                        //
                        $API_KEY = '';
                        

                        $message = '';

                        //  あとでask_chatを入れる
                        if (isset($_POST["ask_chat"])) {

                            
                            $messages[] = [
                                //ロールを設定
                                'role'      => 'system',
                                'content'   => 'あなたはアドバイザーです。ユーザーは留学経験を活かして有益なキャリアを構築したいと考えています。的確な情報を回答してください。'
                            ];

                            // $message = $_POST['message']; // 追加

                            // 最初の質問
                            // $message = '人気YouTuberになるには';
                            // echo "<h2>$message</h2>";
                            // 引数として受け取ったメッセージをAPIに送信して、返答を取得する処理を行う。

                            // 次の質問
                            // $message = '更新頻度はどの位が良い？';
                            // echo "<h2>$message</h2>";
                            // post_params($message);
                                $all_content = '';
                                foreach ($contents as $content) {
                                    $all_content .= $content['content'];
                                }

                                $all_realize = '';
                                foreach ($contents as $content) {
                                    $all_realize .= $content['realize'];
                                }
                                $career = $_POST['career'];
                                $career_style = $_POST['career_style'];


                            // APIを叩いて返答をmessagesに追加
                            function post_params($message)
                            {
                                global $API_KEY;
                                global $messages;
                                global $contents;
                                global $all_content;
                                global $all_realize;

                                $tag = $_POST['tag'];
                                $reference = $_POST['reference'];
                                $career = $_POST['career'];
                                $career_style = $_POST['career_style'];
                                $career_ask = $_POST['career_ask'];
                                $textVolume = $_POST['textVolume'];
                                $selected_skill = $_POST['skill'];
                                $skill_summary = implode(',', $selected_skill);

                                $curl = curl_init('https://api.openai.com/v1/chat/completions');

                                $header = [
                                    'Authorization: Bearer '.$API_KEY,
                                    'Content-type: application/json',
                                ];


                                if(isset($_POST['career_ask'])) {
                                    $tag = $_POST['tag'];
                                    $selected_skill = $_POST['skill'];
                                    $skill_summary = implode(',', $selected_skill);
                                    $reference = $_POST['reference'];
                                    $career = $_POST['career'];
                                    $career_style = $_POST['career_style'];
                                    $career_ask = $_POST['career_ask'];
                                    $textVolume = $_POST['textVolume'];

                                    if($career_ask == '将来のキャリアパスでアピールすべき点を把握') {
                                        $messages[] = [
                                            'role'      => 'user',
                                            // 'content'   =>	$message
                                            'content'   =>	
                                            "私は日本人の大学生です。現在、海外留学をしています。以下の情報を活用して、{$career}を目指すために、これまでの自分の経験からアピールすべきポイントを３つ、理由とともに教えてください。ここで記載するスキルや活かしたい経験、最後に生成した文字数も教えてください。
                                            ・留学経験を通じて身につけた「{$skill_summary}」を活かした内容のアピールポイントの文章にする。
                                            ・これまでの自分の経験：{$all_content}
                                            ・自分の経験から学んだポイント：{$all_realize}
                                            ・将来、自分が目指したいキャリア：{$career}
                                            ・将来、自分が興味のある働き方：{$career_style}
                                            ・「{$career}」を目指す上で、自分の経験からアピールすべきポイントを３つ教えてください。
                                            ・文章は「{$textVolume}」字以内にしてください。
                                            "
                                        ];
                                    } elseif($career_ask == '目標のキャリアに必要なスキルを確認') {
                                        $messages[] = [
                                            'role'      => 'user',
                                            // 'content'   =>	$message
                                            'content'   =>	
                                            "私は日本人の大学生です。現在、海外留学をしています。以下の情報を活用して、{$career}を目指すために必要なスキルを３つ、理由とともに教えてください。
                                            すでに身につけているスキルがあれば、理由とともに教えてください。
                                            ここで記載するスキルや活かしたい経験、最後に生成した文字数も教えてください。
                                            以下の項目を踏まえて回答してください。
                                            ・身につけたスキル：{$skill_summary}
                                            ・今後のキャリアに活かしたい経験：{$all_content}
                                            ・今後のキャリアを考える上で、経験から気づいた点：{$all_realize}
                                            ・将来、自分が目指したいキャリア：{$career}
                                            ・将来、「{$career}」のキャリアを目指す上で、すでに身についていると思われるスキルを褒めて、具体的な理由とともに内容を教えてください。
                                            ・将来、「{$career}」のキャリアを目指す上でに身につけておくべき必要なスキルを３つ教えて欲しい。
                                            ・文章は「{$textVolume}」字以内にしてください。
                                            "
                                        ];
                                    }
                                }
                                

                                $params =  [
                                    'model'     => 'gpt-3.5-turbo',
                                    // 'role'      => 'assistant',
                                    'messages'  =>	$messages
                                ];

                                $options = [
                                    CURLOPT_POST => true,
                                    CURLOPT_HTTPHEADER =>$header,
                                    CURLOPT_POSTFIELDS => json_encode($params,JSON_UNESCAPED_UNICODE),
                                    CURLOPT_RETURNTRANSFER => true,
                                ];
                                curl_setopt_array($curl, $options);
                                // APIにリクエストを送信し、返答を取得。
                                $response = curl_exec($curl);
                                if ($response === false) {
                                    echo 'cURL Error: ' . curl_error($curl);
                                    return;
                                }
                                // 返答はJSON形式で取得。
                                $json_array = json_decode($response, true);

                                $choices = $json_array['choices'];
                            
                                echo '<div>';
                                echo '<p>';
                                foreach($choices as $v){
                                    // アシスタントの返答をmessagesへ追加
                                    $messages[] = $v['message'];
                                    echo nl2br($v['message']['content']).'<br />';
                                }
                                echo '</p>';
                                echo '</div>';

                                return;
                            }

                            post_params($message);

                        }
                    ?>