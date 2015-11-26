<?php
namespace fumbol\common\resources {


    use fumbol\common\data\entities\Match;
    use fumbol\common\Language;
    use fumbol\common\logic\MatchLogic;
    use fumbol\common\UserUtilities;
    use fumbol\common\Utilities;

    class MatchResource extends AbstractResource
    {

        private $user_utilities;
        private $match_logic;

        /*
         * Check si hoy hay partido
         */
        public function check()
        {
            try {

                $this->match_logic = new MatchLogic();

                @$current_match = $this->match_logic->getCurrentMatch();

                $response_data = [];

                if(!is_null($current_match)) {
                    $current_match = $current_match->toArray();
                    $response_data = ['current_match'=>$current_match];
                }

                $this->getApp()->render(
                    200,
                    ['data' => $response_data]
                );

            } catch (\Exception $e) {

                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }
        }

        public function signup() {

            try {
                $this->match_logic = new MatchLogic();
                $this->user_utilities = new UserUtilities();
                $user_token = $this->getApp()->request()->post('token');
                $user_information = $this->user_utilities->getUserInfoFromToken($user_token);

                if ($user_information) {
                    @$current_match = $this->match_logic->getCurrentMatch();
                    if (is_null($current_match)) {
                        throw new \Exception("No Matches for today. Sorry");
                    }
                } else {
                    throw new \Exception("Ese usuario no existe.");
                }

                //-- We have a Match and we have a Valid User. Move along.
                $user_id = $user_information->getUserId();
                $match_id = $current_match->getMatchId();

                //-- Check status and number of players for current match
                // TODO :: IMPLEMENT LOCK
                $response = [];

                if ($current_match->getStatus() == MatchLogic::STATUS_LFM) {
                    //-- Add User to Match!
                    try {
                        $match_player_id = $this->match_logic->addUserToMatch($user_id, $match_id);
                    } catch (\Exception $ie) {
                        //-- TODO:: User Already signed up for this match. Throw specific exception
                        //throw new \Exception(Language::t('USER_ALREADY_SIGNED_UP_FOR_MATCH','Ya estás anotado papá'));
                    }
                } elseif ($current_match->getStatus() == MatchLogic::STATUS_FULL) {
                    //-- TODO:: Match Is full, notify user or add him anyway in a different status? Like, suplente or something?
                } elseif ($current_match->getStatus() == MatchLogic::STATUS_NO_MATCH) {
                    throw new \Exception('No hay partido hoy. Nabo.');
                }

                //-- Check and Create Teams
                $this->match_logic->checkAndCreateTeams($match_id);

                //-- Build Response
                $api_response = $this->buildMatchAndTeamsResponse($current_match);

                $this->getApp()->render(
                    200,
                    ['data' => $api_response]
                );


            }catch (\Exception $e) {
                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }
        }

        public function confirm($user_token,$match_id) {

            try {
                $this->match_logic = new MatchLogic();
                $this->user_utilities = new UserUtilities();

                $user_information = $this->user_utilities->getUserInfoFromToken($user_token);

                if ($user_information) {
                    @$current_match = $this->match_logic->getCurrentMatch();
                } else {
                    throw new \Exception("A quien te comiste gato?");
                }

                if ($current_match->getMatchId() != $match_id) {
                    throw new \Exception("Te acordaste tarde de avisar pibe");
                }

                //-- Ok, we are here. Lests Do this
                $this->match_logic->confirmPlayerInMatch($user_information->getUserId(), $match_id);

                $this->getApp()->render(
                    200,
                    ['data' => "ok"]
                );

            }catch (\Exception $e) {
                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }
        }

        private function buildMatchAndTeamsResponse(Match $match) {

            if(is_null($this->match_logic)) {
                $this->match_logic = new MatchLogic();
            }

            $match_id = $match->getMatchId();

            //-- Build Response. Get current Match and check if we have teams
            $current_teams = $this->match_logic->getMatchTeams($match_id);

            $api_response = ["match" => $match->toArray()];
            //-- Fetch Players In teams Again from DB TODO:: Maybe we can skip this db query with what we have in memory
            $players_in_teams = Utilities::getPlayersInTeams($this->match_logic->getAllMatchPlayers($match_id));

            foreach ($current_teams as $team) {
                $match_team_id = intval($team['match_team_id']);
                $api_response["teams"][$match_team_id] = array_merge($team, ["players" => $players_in_teams[$match_team_id]]);
            }

            return $api_response;
        }

        public function getCurrentMatch() {

            try {

                if(is_null($this->match_logic)) {
                    $this->match_logic = new MatchLogic();
                }

                @$current_match = $this->match_logic->getCurrentMatch();

                if (is_null($current_match)) {
                    throw new \Exception("No Matches for today. Sorry");
                }

                //-- Build Response
                $api_response = $this->buildMatchAndTeamsResponse($current_match);

                $this->getApp()->render(
                    200,
                    ['data' => $api_response]
                );

            }catch (\Exception $e) {
                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }

        }

        public function shuffle() {

            try {

                if(is_null($this->match_logic)) {
                    $this->match_logic = new MatchLogic();
                }

                @$current_match = $this->match_logic->getCurrentMatch();

                if (is_null($current_match)) {
                    throw new \Exception("No Matches for today. Sorry");
                }

                $match_id= $current_match->getMatchId();

                //-- Check and Create Teams
                $this->match_logic->shuffle($match_id);

                //-- Build Response
                $api_response = $this->buildMatchAndTeamsResponse($current_match);

                $this->getApp()->render(
                    200,
                    ['data' => $api_response]
                );

            }catch (\Exception $e) {
                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }

        }
    }





}
