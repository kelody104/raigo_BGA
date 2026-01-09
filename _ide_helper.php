<?php





namespace Bga\GameFramework\Actions {
    
    class CheckAction {
        public function __construct(
            public bool $enabled = true,
        ) {}
    }
    
    
    class Debug {
        public function __construct(
            public bool $reload = false,
        ) {}
    }
}

namespace Bga\GameFramework\Actions\Types {
    
    class IntParam {
        public function __construct(
            ?string $name = null,
            public ?int $min = null, 
            public ?int $max = null,
        ) {}

        public function getValue(string $paramName): int { return 0; }
    }

    
    class BoolParam  {
        public function __construct(
            ?string $name = null,
        ) {}

        public function getValue(string $paramName): bool { return false; }
    }

    
    class FloatParam {
        public function __construct(
            ?string $name = null,
            public ?float $min = null, 
            public ?float $max = null,
        ) {}

        public function getValue(string $paramName): float { return 0; }
    }

    
    class IntArrayParam {
        public function __construct(
            ?string $name = null,
            public ?int $min = null, 
            public ?int $max = null,
        ) {}

        public function getValue(string $paramName): array { return []; }
    }

    
    class StringParam {
        public function __construct(
            ?string $name = null,
            public ?bool $alphanum = false, 
            public ?bool $alphanum_dash = false, 
            public ?bool $base64 = false, 
            public ?array $enum = null,
        ) {}
    
        public function getValue(string $paramName): string { return ''; }
    }

    
    class JsonParam {
        public function __construct(
            ?string $name = null,
            public ?bool $associative = true,
            public ?bool $alphanum = true, 
        ) {}
    
        public function getValue(string $paramName): mixed { return []; }    
    }
}

namespace Bga\GameFramework\States {
    
    class PossibleAction {}
    

    abstract class GameState
    {        
        public \Bga\GameFramework\Db\Globals $globals;
        public \Bga\GameFramework\Notify $notify;
        public \Bga\GameFramework\Legacy $legacy;
        public \Bga\GameFramework\TableOptions $tableOptions;
        public \Bga\GameFramework\UserPreferences $userPreferences;
        public \Bga\GameFramework\TableStats $tableStats;
        public \Bga\GameFramework\PlayerStats $playerStats;
        public \Bga\GameFramework\Components\DeckFactory $deckFactory;
        public \Bga\GameFramework\Components\Counters\CounterFactory $counterFactory;
        public \Bga\GameFramework\Components\Counters\PlayerCounter $playerScore;
        public \Bga\GameFramework\Components\Counters\PlayerCounter $playerScoreAux;

        public ?\Bga\GameFramework\GameStateMachine $gamestate = null;

        public function __construct(
             $game, 
            public int $id, 
            public \Bga\GameFramework\StateType $type,

            public ?string $name = null,
            public string $description = '',
            public string $descriptionMyTurn = '',
            public array $transitions = [],
            public bool $updateGameProgression = false,
            public ?int $initialPrivate = null,
        ) {
        }

        
        public function getRandomZombieChoice(array $choices): mixed {
            return null;
        }

        
        public function getBestZombieChoice(array $choices, bool $reversed = false): mixed {
            return null;
        }
    }
}

namespace Bga\GameFramework {
    enum StateType: string
    {
        case ACTIVE_PLAYER = 'activeplayer';
        case MULTIPLE_ACTIVE_PLAYER = 'multipleactiveplayer';
        case PRIVATE = 'private';
        case GAME = 'game';
        case MANAGER = 'manager';
    }

    
    final class GameStateBuilder
    {
        
        public static function create(): self
        {
            return new self();
        }

        
        public static function gameSetup(int|string $nextStateId = 2): self
        {
            return self::create();
        }

        
        public static function endScore(): self
        {
            return self::create();
        }

        
        public static function gameEnd(): self
        {
            return self::create();
        }

        
        public function name(string $name): self
        {
            return $this;
        }

        
        public function type(StateType $type): self
        {
            return $this;
        }

        
        public function description(string $description): self
        {
            return $this;
        }

        
        public function descriptionMyTurn(string $descriptionMyTurn): self
        {
            return $this;
        }

        
        public function action(string $action): self
        {
            return $this;
        }

        
        public function args(string $args): self
        {
            return $this;
        }

        
        public function possibleActions(array $possibleActions): self
        {
            return $this;
        }

        
        public function transitions(array $transitions): self
        {
            return $this;
        }

        
        public function updateGameProgression(bool $update): self
        {
            return $this;
        }

        
        public function initialPrivate(int $initial): self
        {
            return $this;
        }

        
        public function build(): GameState
        {
            return new GameState();
        }
    }


    abstract class Notify {
        
        public function addDecorator(callable $fn) {
           
        }

        
        public function player(int $playerId, string $notifName, string | NotificationMessage $message = '', array $args = []): void {
            
        }

        
        public function all(string $notifName, string | NotificationMessage $message = '', array $args = []): void {
            
        }
    }

    abstract class Legacy {
        
        public function get(string $key, int $playerId, mixed $defaultValue = null): mixed {
            return null;
        }

        
        public function set(string $key, int $playerId, mixed $value, int $ttl = 365): void {
        }

        
        public function delete(string $key, int $playerId): void {
        }

        
        public function getTeam(mixed $defaultValue = null): mixed {
            return null;
        }

        
        public function setTeam(mixed $value, int $ttl = 365): void {
        }

        
        public function deleteTeam(): void {
        }
    }


    abstract class TableOptions {
        
        public function get(int $optionId): ?int {
            return 0;
        }
    
        
        function isTurnBased(): bool {
            return false;
        }
    
        
        function isRealTime(): bool {
            return false;
        }
    }

    abstract class UserPreferences {
        
        function get(int $playerId, int $prefId): ?int
        {
            return null;
        }
    }

    abstract class TableStats {
        
        public function init(string|array $nameOrNames, int|float|bool $value): void {
        }

        
        public function set(string $name, int|float|bool $value): void {
        }

        
        public function inc(string $name, int|float $delta): void {
        }

        
        public function get(string $name): int|float|bool {
            return 0;
        }
    }

    abstract class PlayerStats {
        
        public function init(string|array $nameOrNames, int|float|bool $value, bool $updateTableStat = false): void {
        }

        
        public function set(string $name, int|float|bool $value, int $player_id): void {
        }

        
        public function setAll(string $name, int|float|bool $value): void {
        }

        
        public function inc(string $name, int|float $delta, int $player_id, bool $updateTableStat = false): void {
        }

        
        public function incAll(string $name, int|float $delta): void {
        }

        
        public function get(string $name, int $player_id): int|float|bool {
            return 0;
        }

        
        public function getAll(string $name): array {
            return [];
        }
    }

    abstract class GameState
    {
        public ?string $name = null;
        public ?StateType $type = null;
        public ?string $description = '';
        public ?string $descriptionMyTurn = '';
        public ?string $action = null;
        public ?string $args = null;
        public ?array $possibleActions = null;
        public ?array $transitions = null;
        public ?bool $updateGameProgression = false;
        public ?int $initialPrivate = null;

        public function toArray(): array
        {
            return [];
        }
    }

    abstract class GamestateMachine
    {
        
        final public function changeActivePlayer(int $playerId): void
        {
            
        }

        
        final public function checkPossibleAction(string $action_name): void
        {
            
        }

        
        final public function getActivePlayerList(): array
        {
            return [];
        }

        
        final public function getPrivateState(int $playerId): array
        {
            return [];
        }

        
        final public function initializePrivateState(int $playerId): void
        {
            
        }

        
        final public function initializePrivateStateForAllActivePlayers(): void
        {
            
        }

        
        final public function initializePrivateStateForPlayers(array $playerIds): void
        {
            
        }

        
        final public function isMutiactiveState(): bool
        {
            return false;
        }

        
        final public function isMultiactiveState(): bool
        {
            return false;
        }

        
        final public function isPlayerActive(int $player_id): bool
        {
            return false;
        }

        
        final public function jumpToState(int $next_state): void
        {
            
        }

        
        final public function nextPrivateState(int $playerId, int|string $transition): void
        {
            
        }

        
        final public function nextPrivateStateForAllActivePlayers(int|string $transition): void
        {
            
        }

        
        final public function nextPrivateStateForPlayers(array $playerIds, int|string $transition): void
        {
            
        }

        
        final public function nextState(string $transition = ''): void
        {
            
        }

        
        final public function reloadState(): array
        {
            return [];
        }

        
        final public function setAllPlayersMultiactive(): void
        {
            
        }

        
        final public function setAllPlayersNonMultiactive(string $next_state): bool
        {
            return false;
        }

        
        final public function setPlayerNonMultiactive(int $player, string $nextState): bool
        {
            return false;
        }

        
        final public function setPlayersMultiactive(array $players, string $nextState, bool $bInactivePlayersNotOnTheList = false): bool
        {
            return false;
        }

        
        final public function setPrivateState(int $playerId, int $newStateId): void
        {
            
        }

        
        final public function state(bool $bSkipStateArgs = false, bool $bOnlyVariableContent = false, bool $bSkipReflexionTimeLoad = false): array
        {
            return [];
        }

        
        final public function state_id(): int
        {
            return 0; 
        }

        
        final public function unsetPrivateState(int $playerId): void
        {
            
        }

        
        final public function unsetPrivateStateForAllPlayers(): void
        {
            
        }

        
        final public function unsetPrivateStateForPlayers(array $playerIds): void
        {
            
        }

        
        final public function updateMultiactiveOrNextState(string $nextStateIfNone): void
        {
            
        }

        
        public function getStatesAsArray(): array {
            return [];
        }

        
        public function getCurrentState(?int $playerId): ?GameState {
            return null;
        }

        
        public function getCurrentStateId(?int $playerId): ?int {
            return null;
        }

        
        public function getCurrentMainState(): ?GameState {
            return null;
        }

        
        public function getCurrentMainStateId(): ?int {
            return null;
        }

        
        public function runStateClassZombie(GameState $state, int $playerId): void {
        }
    }

    class NotificationMessage {
        public function __construct(
            public string $message = '',
            public array $args = [],
        ) {}
    }
    
    abstract class Debug {
        public function playUntil(callable $fn): void {
        }
    }

    abstract class Table
    {
        
        readonly public \Bga\GameFramework\GamestateMachine $gamestate;

        
        readonly public \Bga\GameFramework\Db\Globals $globals;

        
        readonly public \Bga\GameFramework\Notify $notify;

        
        readonly public \Bga\GameFramework\Legacy $legacy;

        
        readonly public \Bga\GameFramework\TableOptions $tableOptions;

        
        readonly public \Bga\GameFramework\UserPreferences $userPreferences;

        
            public \Bga\GameFramework\TableStats $tableStats;

        
            public \Bga\GameFramework\PlayerStats $playerStats;

        
        readonly public \Bga\GameFramework\Components\DeckFactory $deckFactory;

        
        readonly public \Bga\GameFramework\Components\Counters\CounterFactory $counterFactory;

        
        readonly public \Bga\GameFramework\Components\Counters\PlayerCounter $playerScore;

        
        readonly public \Bga\GameFramework\Components\Counters\PlayerCounter $playerScoreAux;

        
        readonly public \Bga\GameFramework\Debug $debug;

        
        public function __construct()
        {
            
        }

        
        final public function debug(string $message): void
        {
            
        }

        
        final public function dump(string $prefix, mixed $object): void
        {
            
        }

        
        final public function error(string $message): void
        {
            
        }

        
        final public function trace(string $message): void
        {
            
        }

        
        final public function warn(string $message): void
        {
            
        }

        
        final public static function DbAffectedRow(): int
        {
            return 0;
        }

        
        final public static function DbGetLastId(): int
        {
            return 0;
        }

        
        final public static function DbQuery(string $sql): null|\mysqli_result|bool
        {
            return null;
        }

        
        final public static function escapeStringForDB(string $string): string
        {
            return ''; 
        }

        
        final public static function getObjectListFromDB(string $sql, bool $bUniqueValue = false): array
        {
            return [];
        }

        
        final public static function getUniqueValueFromDB(string $sql): mixed
        {
            return null;
        }

        
        final public function activeNextPlayer(): int|string
        {
            return '0';
        }

        
        final public function checkAction(string $actionName, bool $bThrowException = true): bool
        {
            return false;
        }

        
        final public function eliminatePlayer(int $player_id): void
        {
            
        }

        
        final public function getActivePlayerId(): string|int
        {
            return '0'; 
        }

        
        final public function getActivePlayerName(): string
        {
            return ''; 
        }

        

        
        final public static function getCollectionFromDB(string $sql, bool $bSingleValue = false): array
        {
            return [];
        }

        
        final public function getCurrentPlayerId(bool $bReturnNullIfNotLogged = false): string|int
        {
            return '0';
        }

        
        final public static function getDoubleKeyCollectionFromDB(string $sql, bool $bSingleValue = false): array
        {
            return [];
        }

        
        final public function getGameLanguage(): string
        {
            return '';
        }

        
        public function getGameProgression()
        {
            
        }

        
        final public function getGameStateValue(string $label, ?int $default = null): int|string
        {
            return '0';
        }

        
        final public function getGameUserPreference(int $playerId, int $prefId): ?int
        {
            return 0;
        }

        
        final public function getGameinfos(): array
        {
            return [];
        }

        
        final public function getNextPlayerTable(): array
        {
            return [];
        }

        
        final public static function getNonEmptyCollectionFromDB(string $sql): array
        {
            return [];
        }

        
        final public static function getNonEmptyObjectFromDB(string $sql): array
        {
            return [];
        }

        
        final public static function getObjectFromDB(string $sql): array
        {
            return [];
        }

        
        final public function getPlayerAfter(int $playerId): int
        {
            return 0;
        }

        
        final public function getPlayerBefore(int $playerId): int
        {
            return 0;
        }

        
        final public function getPlayerColorById(int $player_id): string
        {
            return '';
        }

        
        final public function getPlayerNameById(int $player_id): string
        {
            return '';
        }

        
        final public function getPlayerNoById(int $player_id): int|string
        {
            return '0';
        }

        
        final public function getPlayersNumber(): int
        {
            return 0;
        }

        
        public function getPlayerCount(): int
        {
            return 0;
        }

        
        public function getSpecificColorPairings(): array
        {
            return [];
        }

        
        final public function getStat(string $name, ?int $player_id = null): int
        {
            return 0;
        }

        
        final public function giveExtraTime(int $playerId, ?int $specificTime = null): void
        {
            
        }

        
        final public function incGameStateValue(string $label, int $increment): int
        {
            return 0;
        }

        
        final public function incStat(int $inc, string $name, ?int $playerId = null, bool $bDoNotLoop = false): void
        {
            
        }

        
        final public function initStat(string $tableOrPlayer, string $name, int $value, ?int $playerId = null): void
        {
            
        }

        
        final public function isAsync(): bool
        {
            return false;
        }

        
        final public function isRealtime(): bool
        {
            return false;
        }

        
        final public function isSpectator(): bool
        {
            return false;
        }

        
        final public function loadPlayersBasicInfos()
        {
            
        }

        
        final public function logTextForModeration(int $player_id, string $message): void
        {
            
        }

        
        final public function notifyAllPlayers(string $notificationType, string $notificationLog, array $notificationArgs): void
        {
            
        }

        
        final public function notifyPlayer(int $playerId, string $notificationType, string $notificationLog, array $notificationArgs): void
        {
            
        }

        
        final public function reattributeColorsBasedOnPreferences(array $players, array $colors): void
        {
            
        }

        
        final public function reloadPlayersBasicInfos(): void
        {
            
        }

        
        final public function removeLegacyData(int $playerId, string $key): void
        {
            
        }

        
        final public function removeLegacyTeamData(): void
        {
            
        }

        
        final public function retrieveLegacyData($playerId, $key): array
        {
            return [];
        }

        
        final public function retrieveLegacyTeamData(): array
        {
            return [];
        }

        
        final public function setGameStateInitialValue(string $label, int $value): void
        {
            
        }

        
        final public function setGameStateValue(string $label, int $value): void
        {
            
        }

        
        final public function setStat(int $value, string $name, ?int $player_id = null, bool $bDoNotLoop = false): void
        {
            
        }

        
        final public function stMakeEveryoneActive(): void
        {
            
        }

        
        final public function storeLegacyData(int $playerId, string $key, array $data, int $ttl = 365): void
        {
            
        }

        
        final public function storeLegacyTeamData(array $data, int $ttl = 365): void
        {
            
        }

        
        final public function undoRestorePoint(): void
        {
            
        }

        
        final public function undoSavepoint(): void
        {
            
        }

        
        public function upgradeTableDb($from_version)
        {
            
        }

        
        protected function _(string $text): string
        {            
            return '';
        }

        
        final public function activePrevPlayer(): void
        {
            
        }

        
        final public function createNextPlayerTable(array $players, bool $bLoop = true): void
        {
            
        }

        
        abstract protected function getAllDatas(): array;

        
        final public function getCurrentPlayerColor(): string
        {
            return '';
        }

        
        final public function getCurrentPlayerName($bReturnEmptyIfNotLogged = false): string
        {
            return '';
        }

        
        final public function getPrevPlayerTable(): array
        {
            return [];
        }

        
        final protected function initGameStateLabels(array $labels): void
        {
            
        }

        
        protected function initTable(): void
        {
            
        }

        
        final public function isCurrentPlayerZombie(): bool
        {
            return false;
        }

        
        abstract protected function setupNewGame($players, $options = []);

        
        

        
        protected function getNew(string $objectName): \Bga\GameFramework\Components\Deck {
            return $this->deckFactory->createDeck('');
        }
    
        
        function applyDbUpgradeToAllDB(string $sql): void {
        }

        
        function getGenericGameInfos(string $api, array $args = []) : array {
            return [];
        }

        
        static function getBgaEnvironment(): string {
            return '';
        }
    }

    
    class UserException extends \Exception
    {
        
        public function __construct(string|NotificationMessage $message)
        {
            parent::__construct();
        }
    }

    
    class SystemException extends \Exception
    {
        
        public function __construct(string|NotificationMessage $message)
        {
            parent::__construct();
        }
    }

    
    class VisibleSystemException extends \Exception
    {
        
        public function __construct(string|NotificationMessage $message)
        {
            parent::__construct();
        }
    }


}

namespace Bga\GameFramework\Db {
    abstract class Globals
    {
        
        public function delete(string ...$names): void
        {
            
        }

        
        public function get(string $name, mixed $defaultValue = null, ?string $class = null): mixed
        {
            return null;
        }
        
        
        public function getAll(string ...$names): array
        {
            return [];
        }

        
        public function has(string $name): bool
        {
            return false;
        }

        
        public function inc(string $name, int $step): int
        {
            return 0;
        }

        
        public function set(string $name, mixed $value): void
        {
            
        }
    }

}

namespace Bga\GameFramework\Components {

    abstract class Deck extends \Deck
    {
        var $autoreshuffle;
        var $autoreshuffle_trigger; 

        
        function init(string $table) {}

        
        function createCards(array $cards, string $location = 'deck', ?int $location_arg = null) {}
        
        
        function getExtremePosition(bool $getMax , string $location): int
        {
            return false;
        }
        
        
        function shuffle(string $location)
        {
        }
        
        
        function pickCard(string $location, int $player_id): ?array
        {
            return [];
        }
        
        
        function pickCards(int $nbr, string $location, int $player_id): ?array
        {
            return [];
        }

        
        function pickCardForLocation(string $from_location, string $to_location, int $location_arg=0 ): ?array
        {
            return [];
        }

        
        function pickCardsForLocation(int $nbr, string $from_location, string $to_location, int $location_arg=0, bool $no_deck_reform=false ): ?array
        {
            return [];
        }
        
        
        function getCardOnTop(string $location): ?array
        {
            return [];
        }

        
        function getCardsOnTop(int $nbr, string $location): ?array
        {
            return [];
        }
        
        
        function moveCard(int $card_id, string $location, int $location_arg=0): void
        {
        }

        
        function moveCards(array $cards, string $location, int $location_arg=0): void
        {
        }
        
        
        function insertCard(int $card_id, string $location, int $location_arg ): void
        {
        }

        
        function insertCardOnExtremePosition(int $card_id, string $location, bool $bOnTop): void
        {
        }

        
        function moveAllCardsInLocation(?string $from_location, ?string $to_location, ?int $from_location_arg=null, int $to_location_arg=0 ): void
        {
        }

        
        function moveAllCardsInLocationKeepOrder(string $from_location, string $to_location): void
        {
        }
        
        
        function getCardsInLocation(string|array $location, ?int $location_arg = null, ?string $order_by = null ): array
        {
            return [];
        }
        
        
        function getPlayerHand(int $player_id): array
        {
            return [];
        }
        
         
        function getCard(int $card_id ): ?array
        {
            return [];
        }
        
         
        function getCards(array $cards_array ): array
        {
            return [];
        }
        
        
        function getCardsFromLocation(array $cards_array, string $location, ?int $location_arg = null ): array
        {
            return [];
        }
        
        
        function getCardsOfType(mixed $type, ?int $type_arg=null ): array
        {
            return [];
        }
        
        
        function getCardsOfTypeInLocation(mixed $type, ?int $type_arg, string $location, ?int $location_arg = null ): array
        {
            return [];
        }
        
        
        function playCard(int $card_id): void
        {
        }
        
        
        function countCardInLocation(string $location, ?int $location_arg=null): int|string
        {
            return '0';
        }
        
        
        function countCardsInLocation(string $location, ?int $location_arg=null): int|string
        {
            return '0';
        }
        
        
        function countCardsInLocations(): array
        {
            return [];
        }
        
        
        function countCardsByLocationArgs(string $location): array
        {
            return [];
        }
    }

    final class DeckFactory {
        
        public function createDeck(string $tableName): Deck {
            return new class extends Deck{}();
        }
    }

}

namespace Bga\GameFramework\Components\Counters {        
    
    final class CounterFactory {
        
        public function createPlayerCounter(string $name, ?int $min = 0, ?int $max = null): PlayerCounter {
            return new class extends PlayerCounter {}();
        }

        
        public function createTableCounter(string $name, ?int $min = 0, ?int $max = null): TableCounter {
            return new class extends TableCounter{}();
        }
    }

    abstract class OutOfRangeCounterException extends \BgaSystemException
    {
    }
    
    abstract class UnknownPlayerException extends \BgaSystemException
    {
    }

    
    abstract class PlayerCounter {
        
        public function initDb(array $playerIds, int $initialValue = 0) {
        }

        
        public function get(int $playerId): int {
            return 0;
        }

        
        public function set(int $playerId, int $value, ?\Bga\GameFramework\NotificationMessage $message = new \Bga\GameFramework\NotificationMessage()): int {
            return 0;
        }

        
        public function inc(int $playerId, int $inc, ?\Bga\GameFramework\NotificationMessage $message = new \Bga\GameFramework\NotificationMessage()): int {
            return 0;
        }

        
        public function getMin(): int {
            return 0;
        }

        
        public function getMax(): int {
            return 0;
        }
        
        
        public function getAll(): array {
            return [];
        }

        
        public function setAll(int $value, ?\Bga\GameFramework\NotificationMessage $message = new \Bga\GameFramework\NotificationMessage()): int {
            return 0;
        }

        
        public function fillResult(array &$result, ?string $fieldName = null) {
        }
    }

    
    abstract class TableCounter {
        
        public function initDb(int $initialValue = 0) {}

        
        public function get(): int {
            return 0;
        }

        
        public function set(int $value, ?\Bga\GameFramework\NotificationMessage $message = new \Bga\GameFramework\NotificationMessage()): int {
            return 0;
        }

        
        public function inc(int $inc, ?\Bga\GameFramework\NotificationMessage $message = new \Bga\GameFramework\NotificationMessage()): int {
            return 0;
        }

        
        public function fillResult(array &$result, ?string $fieldName = null) {
        }
    }
}


namespace Bga\GameFramework\Helpers {
    final class Json {

        
        public static function decode(string $json_obj, ?string $class = null): mixed {
            return null;        
        }

        
        public static function encode(mixed $obj): string {
            return '';
        }
    }
}

namespace Bga\GameFramework\GameResult {
    class Player
    {
        public function __construct(
            public int $id,
            public string $name,
            public string $color = '000000',
            public ?int $score = null,
            public ?int $scoreAux = null,
        ) {}

        
        public static function fromPlayerDb(array $playerDb): self {
            return new self(0, '');
        }

        
        public static function fromPlayersDb(array $playersDb): array {
            return [];
        }
    }

    class GameResult
    {

        
        public static function individualRanking(
            array $players,
            bool $reverseScore = false,
            bool $reverseScoreAux = false,
        ) {
            return new self();
        }
    }
}

namespace {
    exit("This file should not be included, only analyzed by your IDE");

    
    const APP_GAMEMODULE_PATH = "";

    
    const APP_BASE_PATH = "";

    
    function clienttranslate(string $text): string
    {
        return ''; 
    }

    
    function totranslate(string $text): string
    {
        return ''; 
    }

    function bga_rand(int $min, int $max): int {
        return 0;
    }

    abstract class APP_Template
    {
        
        final public function begin_block(string $template_name, string $block_name): void
        {
            
        }

        
        final public function begin_subblock(string $template_name, string $block_name): void
        {
            
        }

        
        final public function insert_block(string $block_name, array $tpl = []): void
        {
            
        }

        
        final public function insert_subblock(string $block_name, array $tpl = []): void
        {
            
        }
    }

    abstract class game_view
    {
        
        readonly protected \Bga\GameFramework\Table $game;

        
        readonly protected APP_Template $page;

        
        protected array $tpl;

        
        abstract public function build_page($viewArgs);

        
        final protected function _(string $text): string
        {
            return ''; 
        }

        
        abstract protected function getGameName();

        final protected function raw(string $string): array
        {
            return [];
        }

        
        protected function getCurrentPlayerId(): int
        {
            return 0;
        }
    }

    
    abstract class Table extends \Bga\GameFramework\Table {}

    
    const AT_int = 0;

    
    const AT_posint = 1;

    
    const AT_float = 2;

    
    const AT_email = 3;

    
    const AT_url = 4;

    
    const AT_bool = 5;

    
    const AT_enum = 6;

    
    const AT_alphanum = 7;

    
    const AT_numberlist = 13;

    
    const AT_alphanum_dash = 27;

    
    const AT_json = 32;

    
    const AT_base64 = 33;

    abstract class APP_GameAction
    {
        
        protected \Bga\GameFramework\Table $game;

        
        protected string $view = "";

        
        protected array $viewArgs = [];

        
        public function __default()
        {
            
        }

        
        final protected function ajaxResponse(): void
        {
            
        }

        
        final protected function getArg(string $argName, int $argType, bool $bMandatory = false, mixed $default = null, array $argTypeDetails = [], bool $bCanFail = false): mixed
        {
            return null;
        }

        
        final protected function isArg($argName): bool
        {
            return false;
        }

        
        final protected function setAjaxMode(): void
        {
            
        }

        
        protected function getCurrentPlayerId(): int
        {
            return 0;
        }
    }

    

    
    const FEX_NOCODE = 100;

    
    class feException extends Exception
    {
        public function __construct($message, $expected = false, $visibility = true, $code=100, $publicMsg='', public ?array $args = null) {
        }
    }

    
    class BgaSystemException extends feException
    {
        
        public function __construct($message, $code=100, ?array $args = null) {
        }
    }

    
    class BgaVisibleSystemException extends BgaSystemException
    {
        
        public function __construct($message, $code=100, ?array $args = null) {
        }
    }

    
    class BgaUserException extends BgaVisibleSystemException
    {
        
        public function __construct($message, $code=100, ?array $args = null) {
        }
    }

    
    abstract class Deck
    {
        
    }
}
