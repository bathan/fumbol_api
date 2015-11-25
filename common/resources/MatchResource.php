<?php
namespace fumbol\common\resources {


    use fumbol\common\Language;
    use fumbol\common\logic\MatchLogic;
    use fumbol\common\UserUtilities;

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
                $this->user_utilities = new UserUtilities();
                $user_token = $this->getApp()->request()->post('token');
                $user_information = $this->user_utilities->getUserInfoFromToken($user_token);

                if($user_information) {
                    //-- Proceed and check if there is room in the current match
                    $this->match_logic = new MatchLogic();
                    @$current_match = $this->match_logic->getCurrentMatch();

                    if(is_null($current_match)) {
                        throw new \Exception("No Matches for today. Sorry");
                    }

                    $user_id = $user_information->getUserId();
                    $match_id = $current_match->getMatchId();

                    //-- Check status and number of players for current match
                    // TODO :: IMPLEMENT LOCK
                    $response = [];

                    if($current_match->getStatus() == MatchLogic::STATUS_LFM) {
                        //-- Add User to Match!
                        try {
                            $match_player_id = $this->match_logic->addUserToMatch($user_id,$match_id);
                        }catch (\Exception $ie) {
                            //-- TODO:: User Already signed up for this match. Throw specific exception
                            //throw new \Exception(Language::t('USER_ALREADY_SIGNED_UP_FOR_MATCH','Ya estás anotado papá'));
                        }

                        //-- Build Response. Get current Match and check if we have teams
                        $current_teams = $this->match_logic->getMatchTeams($match_id);

                        if(count($current_teams)==0) {
                            //-- Check if we need to create teams

                            $all_players = $this->match_logic->getAllMatchPlayers($match_id);
                            $max_players_cap = (count($all_players) == MatchLogic::MAX_PLAYERS_PER_MATCH);
                            $players_in_teams = $this->getPlayersInTeams($all_players);
                            $no_teams = count($players_in_teams) == 0;

                            if ($max_players_cap && $no_teams) {
                                //-- Assign Teams

                                //-- TODO :: /// Player Selection Plugin Framework

                                /// --- SHUFFLE SELECTOR V 1.0

                                shuffle($all_players);
                                $teams = array_chunk($all_players, (MatchLogic::MAX_PLAYERS_PER_MATCH / 2), true);

                                //-- We have two teams
                                foreach($teams as $t_id=>$players) {
                                    //--Insert Team
                                    $match_team_id =$this->match_logic->addMatchTeam($match_id);
                                    $list_of_user_ids = [];

                                    //-- Update users in this match, with its correpondant team id
                                    foreach($players as $p) {
                                        $list_of_user_ids[] = $p['user_id'];
                                    }

                                    if(count($list_of_user_ids)>0) {
                                        $this->match_logic->assignMatchTeamIdToPlayers($match_id,$match_team_id,$list_of_user_ids);
                                    }
                                }
                            }
                        }
                    }
                    //-- Build Response. Get current Match and check if we have teams
                    $current_teams = $this->match_logic->getMatchTeams($match_id);

                    $api_response = ["match"=>$current_match->toArray()];
                    //-- Fetch Players In teams Again from DB TODO:: Maybe we can skip this db query with what we have in memory
                    $players_in_teams = $this->getPlayersInTeams($this->match_logic->getAllMatchPlayers($match_id));

                    foreach($current_teams as $team) {
                        $match_team_id = $team['match_team_id'];

                        $api_response["teams"][$match_team_id] = array_merge($team,["players"=>$players_in_teams[$match_team_id]]);
                    }

                    $this->getApp()->render(
                        200,
                        ['data' => $api_response]
                    );


                }
            }catch (\Exception $e) {
                $this->getApp()->render(
                    200,
                    ['error' => $e->getMessage()]
                );
            }



        }

        private function getPlayersInTeams($all_players) {
            $players_in_teams = [];

            foreach ($all_players as $sp) {
                $match_team_id = $sp['match_team_id'];
                if (!is_null($match_team_id)) {
                    //-- This lets us know that we have no teams selected yet.
                    $players_in_teams[$sp['match_team_id']][] = $sp;
                }
            }

            return $players_in_teams;

        }

    }



}
