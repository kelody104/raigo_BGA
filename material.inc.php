<?php

/**
 * 駒の種類定義（1〜108）
 * number: 駒番号（1-108）
 * name: 駒の名前
 * code: 効果処理用コード
 * type: 駒の種類
 * weight: 駒の重量
 * effect: 駒の効果説明（UIで表示）
 */
$this->piece_types = array(
    1 => array('number' => 1, 'name' => '一', 'type' => 'otonashi', 'weight' => 1, 'effect' => '', 'count' => 4),
    2 => array('number' => 2, 'name' => '二', 'type' => 'otonashi', 'weight' => 2, 'effect' => '', 'count' => 4),
    3 => array('number' => 3, 'name' => '三', 'type' => 'otonashi', 'weight' => 3, 'effect' => '', 'count' => 4),
    4 => array('number' => 4, 'name' => '四', 'type' => 'otonashi', 'weight' => 4, 'effect' => '', 'count' => 4),
    5 => array('number' => 5, 'name' => '五', 'type' => 'otonashi', 'weight' => 5, 'effect' => '', 'count' => 4),
    6 => array('number' => 6, 'name' => '六', 'type' => 'otonashi', 'weight' => 6, 'effect' => '', 'count' => 4),
    7 => array('number' => 7, 'name' => '七', 'type' => 'otonashi', 'weight' => 7, 'effect' => '', 'count' => 4),
    8 => array('number' => 8, 'name' => '八', 'type' => 'otonashi', 'weight' => 8, 'effect' => '', 'count' => 0),
    9 => array('number' => 9, 'name' => '雷', 'type' => 'kotodama', 'weight' => 1, 'effect' => '', 'count' => 8),
    10 => array('number' => 10, 'name' => '蛇', 'type' => 'kotodama', 'weight' => 2, 'effect' => '', 'count' => 7),
    11 => array('number' => 11, 'name' => '斬', 'type' => 'kotodama', 'weight' => 3, 'effect' => '', 'count' => 6),
    12 => array('number' => 12, 'name' => '陣', 'type' => 'kotodama', 'weight' => 4, 'effect' => '', 'count' => 5),
    13 => array('number' => 13, 'name' => '轟', 'type' => 'kotodama', 'weight' => 5, 'effect' => '', 'count' => 4),
    14 => array('number' => 14, 'name' => '霧', 'type' => 'kotodama', 'weight' => 6, 'effect' => '', 'count' => 3),
    15 => array('number' => 15, 'name' => '瞬', 'type' => 'kotodama', 'weight' => 7, 'effect' => '', 'count' => 2),
    16 => array('number' => 16, 'name' => '浄', 'type' => 'kotodama', 'weight' => 8, 'effect' => '', 'count' => 1),
    17 => array('number' => 17, 'name' => '覇', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    18 => array('number' => 18, 'name' => '天', 'type' => 'kotodama', 'weight' => 9, 'effect' => '自分または味方の塔を１つ選ぶ。\nその塔の１番上の駒と、捨て駒もしくは全プレイヤーの内駒から１つ駒を選び、選んだ駒の場所を入れ替える。', 'count' => 0),
    19 => array('number' => 19, 'name' => '啓', 'type' => 'kotodama', 'weight' => 9, 'effect' => '２段以上の塔を保持していて、空いている桜門があるプレイヤーから１人選ぶ。\nそのプレイヤーの塔を１つ選び、好きな部分から上下２つに分け、片方をそのまま空いている桜門へ置く。', 'count' => 0),
    20 => array('number' => 20, 'name' => '和', 'type' => 'kotodama', 'weight' => 9, 'effect' => '自分の３段以上の塔を１つ選び、その塔の駒を全て公開する。\n公開した駒の重さの合計が…\n・１１の場合 → ２点獲得\n・３３の場合 → ４点獲得\n公開した駒を完成役と同様にプレイマット横に並べて置く。\n・１１、３３以外の場合→１点失う\n公開した駒を捨て駒として峡谷に置く。', 'count' => 0),
    21 => array('number' => 21, 'name' => '禅', 'type' => 'kotodama', 'weight' => 9, 'effect' => '全プレイヤーは、体内の最も重い駒を１つ選び、その駒を右隣のプレイヤーの体内へ移動させる。\nその後、使用者の判断で、もう１度だけこの効果を適用させることも出来る。', 'count' => 0),
    22 => array('number' => 22, 'name' => '楼', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『一発奥義』\n楼を「発」する手番中に、他の駒を「積･発」することは出来ない。\n自分と味方の塔の中から１つ選び、その塔の駒を好きな順番で積み直す。', 'count' => 0),
    23 => array('number' => 23, 'name' => '龍', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『口寄せ奥義』\n自分の３段以上の塔を、１つ崩して捨て駒にすることで、その空いた桜門の上に、龍を表向きに「積」する。\n龍が、桜門の上に表向きで見えている間、龍の効果を毎手番「発」することが可能となる。\n※龍は「発」した後も桜門に留まる。\n《龍の効果》\n自分と味方以外の内駒から１つ選び、その駒を奪う。奪った駒は、自分の空いている手へ移動する。\n※手が空いていない時は「発」出来ない。', 'count' => 0),
    24 => array('number' => 24, 'name' => '琥', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『口寄せ奥義』\n自分の３段以上の塔を、１つ崩して捨て駒にすることで、その空いた桜門の上に、琥を表向きに「積」する。\n琥が、桜門の上に表向きで見えている間、琥の効果を毎手番「発」することが可能となる。\n※琥は「発」した後も桜門に留まる。\n《琥の効果》\n全ての塔の中から１つ選び、その塔の１番上の駒を捨て駒にする。', 'count' => 0),
    25 => array('number' => 25, 'name' => '舞', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『持続奥義』\n舞をプレイマットの頭に表向きで置く。\nその後、自分を対象とする「雷」「蛇」「斬」の効果を必ず防ぐ。\n防いだ言霊は捨て駒にならず、舞の横に並べて置き蓄積される。\n蓄積された駒の重さが「３以上」になった時点で、舞を含む蓄積された駒は全て捨て駒となり、舞の効果が終了する。\n※舞の効果で防ぐ前に「陣」や「轟」の効果を適用することは可能。\n※雷轟合戦中に舞の効果は適用されない。', 'count' => 0),
    26 => array('number' => 26, 'name' => '忍', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『受動奥義』\n忍は「発」することが出来ない。\n「完成役」または「捨て駒」となることで「忍」が表向きになったとき、即座に以下の効果を２回適用する。\n全プレイヤーの「体内・手・峡谷」にある駒の中から２駒を選び、選んだ駒の場所を入れ替える。\nただし、峡谷にある駒を選ぶ場合、重さ８以上の言霊は選べない。\n効果の対象は、忍を最後に所持していたプレイヤーが指定する。', 'count' => 0),
    27 => array('number' => 27, 'name' => '零', 'type' => 'kotodama', 'weight' => 9, 'effect' => '全プレイヤーは、体内の駒を全て雷山へ表向きに戻す。\n戻した駒は全て雷山として扱う。\n※捨て駒と混ざらないように注意する。\n※雷山がない場合は「発」出来ない。', 'count' => 0),
    28 => array('number' => 28, 'name' => '怒', 'type' => 'kotodama', 'weight' => 9, 'effect' => '全プレイヤーは、自身の峡谷に置かれた「斬」の数だけ、自身の塔に斬の効果を適用する。\n効果の対象は各々自分が決める。\n※塔が無い、または無くなった場合、残りの斬の効果は適用しない。 \n※この効果に陣の効果を使うことは出来ない。', 'count' => 0),
    29 => array('number' => 29, 'name' => '崩', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『受動奥義』\n崩は「発」することが出来ない。\n崩が、別の言霊の効果によって「体内または手」へ移動したとき、即座に以下の効果を適用する。\n崩の移動先の内駒は、崩を含め全て捨て駒となる。\n※浄の効果後『崩』を所持しているならば、崩の効果は適用される。', 'count' => 0),
    30 => array('number' => 30, 'name' => '月', 'type' => 'kotodama', 'weight' => 9, 'effect' => '使用者の内駒から１つ捨て駒にする。他のプレイヤーは、使用者の捨てた駒と同じ駒が「体内または手」にあれば、その駒を全て捨て駒にする。', 'count' => 0),
    31 => array('number' => 31, 'name' => '嵐', 'type' => 'kotodama', 'weight' => 9, 'effect' => '全プレイヤーの「体内または手」にある「雷」を全て回収する。\n回収した「雷」の数と同じ回数、以下の効果を適用する。\n最も高い塔の中から１つ選び、「塔の解放」の処理を行う。\nこの効果は、２段以下の塔にも適用される。\nその後、回収した「雷」を全て捨て駒にする。\n※ 「陣」と「轟」の効果は適用出来ない。', 'count' => 0),
    32 => array('number' => 32, 'name' => '蒼', 'type' => 'kotodama', 'weight' => 9, 'effect' => '持続奥義』\n蒼をプレイマットの頭に表向きで置く。\nその後全プレイヤーは、塔を６段積み上げたとしても「塔の解放」は適用されず、積み上げた６段目の駒は、蒼の横に並べて置き蓄積される。\n蓄積された駒の重さが「６以上」になった時点で、蒼を含む蓄積された駒は全て捨て駒となり、蒼の効果が終了する。\n※複数の蒼が起動している場合は、６段目を「積」したプレイヤーが蓄積される蒼を選ぶ。', 'count' => 0),
    33 => array('number' => 33, 'name' => '美', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『受動奥義』\n美は「発」することが出来ない。\n美が「体内または手」に置いてあり、他のプレイヤーが「蛇の効果」を使用したとき、即座に以下の効果を適用する。\n蛇の効果対象は、必ず「美」になる。\n※美の効果を適用する前に「陣」の効果を適用することは出来ない。', 'count' => 0),
    34 => array('number' => 34, 'name' => '麗', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『受動奥義』\n麗は「発」することが出来ない。\n全プレイヤーの最終手番が終了した時点で、麗が自身の「体内または手」に置いてあり、その他の内駒が全て「音無」だった場合、即座に以下の効果を適用する。\n内駒の音無１つにつき、陽玉を１つ獲得する。', 'count' => 0),
    35 => array('number' => 35, 'name' => '幻', 'type' => 'kotodama', 'weight' => 9, 'effect' => '捨て駒から１つ選び、豪雷と入れ替える。\n選んだ駒が豪雷となる。\n※２人戦の場合\nセットアップ時に除外した駒を、自分の体内もしくは手の空いている場所に配置する。', 'count' => 0),
    36 => array('number' => 36, 'name' => '鳴', 'type' => 'kotodama', 'weight' => 9, 'effect' => '「雷、蛇、斬、陣、轟、霧、瞬、浄」のどれか１つと同じ効果を適用する。', 'count' => 0),
    37 => array('number' => 37, 'name' => '赫', 'type' => 'kotodama', 'weight' => 9, 'effect' => '全プレイヤーは、次の手番のみ強制的に「現」が０個となる。', 'count' => 0),
    38 => array('number' => 38, 'name' => '潤', 'type' => 'kotodama', 'weight' => 9, 'effect' => '自分の体内から駒を好きな数選び「峡谷」へ捨てる。\n捨てた駒と同じ数「峡谷」から駒を選び、体内に配置する。\nただし峡谷から「複数の同じ駒」と「奥義駒」は選べない。\n※体内に駒がない場合は「発」できない。\n※峡谷の駒数よりも多い駒を体内から捨てることはできない。', 'count' => 0),
    39 => array('number' => 39, 'name' => '樹', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『口寄せ奥義』\n自分の３段以上の塔を、１つ崩して捨て駒にすることで、その空いた桜門の上に、樹を表向きに「積」する。\nこの塔を「樹の塔」と呼ぶ。\n「樹の塔」は以下の効果を適用する。\n《樹の塔の効果》\n樹の塔への言霊の効果を無効化する。また効果の対象として選ぶことも出来ない。\nこの塔へ積む駒は全て表向きでなければならない。\n※この塔が最も高い場合、２番目に高い塔が「雷」の効果対象となる。', 'count' => 0),
    40 => array('number' => 40, 'name' => '麟', 'type' => 'kotodama', 'weight' => 9, 'effect' => '自分以外の２段以上の塔から１つ選ぶ。\n選んだ塔の中の好きな駒を１つ選び、その駒を麟があった手に移動させる。', 'count' => 0),
    41 => array('number' => 41, 'name' => '鬼', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『持続奥義』\n鬼をプレイマットの頭に表向きで置く。\nその後、自分と味方以外のプレイヤーが重さ８以下の言霊の効果を適用して処理を終えたとき、自分の体内に空きがある場合に以下の効果を適用する。\n体内が全て埋まるまで雷山から駒を引く。\n鬼は捨て駒となり効果が終了する。\n※複数の鬼が起動していた場合、重さ８以下の言霊を使用したプレイヤーが効果適用者の順番を決める。\n※雷山が無くなった場合、そこで効果は終了となる。', 'count' => 0),
    42 => array('number' => 42, 'name' => '縛', 'type' => 'kotodama', 'weight' => 9, 'effect' => '全プレイヤーの内駒の中から１つ選び、その駒に「縛」を表向きで重ねて置く。\n「縛を置かれた駒」は「積・発・駒の移動」を行うことが出来ない。\nまた、「縛を置かれた駒」は全ての言霊の効果を受けず、効果の対象として選ぶことも出来ない。\n「縛」を置かれたプレイヤーの、次の手番が終わると「縛」は捨て駒となり、効果が終了する。\n※縛と縛を置かれた駒は１つの駒として扱い、重さ順による体内の置き直しも行えない。', 'count' => 0),
    43 => array('number' => 43, 'name' => '影', 'type' => 'kotodama', 'weight' => 9, 'effect' => '「雷、蛇、斬、陣、轟、霧、瞬、浄」のいずれかの言霊と同じ効果を適用することが出来る。\nただし適用するには、まず適用したい言霊と同じ重さの音無を、自分の内駒から捨て駒にしなければならない。\n※自分の内駒に音無が無い場合は「発」出来ない。', 'count' => 0),
    44 => array('number' => 44, 'name' => '鳳', 'type' => 'kotodama', 'weight' => 9, 'effect' => 'プレイヤーを１人選び、そのプレイヤーの内駒と、自分の内駒を全て入れ替える。\nその後、両プレイヤーは入れ替えた内駒を「体内または手」の好きな場所に置き直す。\n※自分または相手の内駒が無い場合も発することが出来る。', 'count' => 0),
    45 => array('number' => 45, 'name' => '焔', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『一発奥義』\n焔を「発」する手番中に、他の駒を「積･発」することは出来ない。\n塔がある「桜門」を１つ選び、その桜門の前に「焔」を置くことで桜門を炎上状態にする。\n炎上状態の桜門に置かれている塔は「隠駒」が動くたびに、１番下の駒が捨て駒となる。\n塔が無くなると「焔」は捨て駒となり効果が終了する。\n※「雷停中の塔」がある「桜門」は、焔の対象にすることが出来ない。\n※炎上中の桜門にある塔を雷停した場合、雷停の効果が終わるまで焔の効果も停止する。', 'count' => 0),
    46 => array('number' => 46, 'name' => '姫', 'type' => 'kotodama', 'weight' => 9, 'effect' => '他プレイヤーの、体内の駒から１つ選ぶ。\n選んだ駒を「姫」があった手へ移動する。\nその後、選んだ駒が元あった場所へ「姫」を移動する。', 'count' => 0),
    47 => array('number' => 47, 'name' => '涼', 'type' => 'kotodama', 'weight' => 9, 'effect' => '全プレイヤーは内駒を全て表向きにする。\n表になった駒は「積」するまで表のまま。', 'count' => 0),
    48 => array('number' => 48, 'name' => '風', 'type' => 'kotodama', 'weight' => 9, 'effect' => '雷山から《プレイ人数＋１》駒を引く。その中から１つ選び自分の「手」に置く。\n残った駒は表向きで雷山に戻す。\n戻した駒は全て雷山として扱う。\n※捨て駒と混ざらないように注意する。\n※雷山が《プレイ人数＋１》駒以上ない場合「発」出来ない', 'count' => 0),
    49 => array('number' => 49, 'name' => '凛', 'type' => 'kotodama', 'weight' => 9, 'effect' => '自分または味方の塔の中から、役が完成している塔を１つ選び公開する。\nその後、雷山から駒を１つ引く。\n引いた駒が…\n［音無の場合］\n→ 役の点数＋２点 を獲得する。\n［言霊の場合］\n→ 役の点数が０点になり、全て捨て駒になる。\n雷山から引いた駒は捨て駒にする。\n※雷山がない場合は「発」出来ない', 'count' => 0),
    50 => array('number' => 50, 'name' => '滅', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『持続奥義』\n滅をプレイマットの頭に表向きで置く。\nその後、効果を適用した言霊は捨て駒にならず、滅の横に並べて置き蓄積される。\nただし奥義駒は蓄積されない。\n蓄積された駒の重さが「10以上」になった手番の最後に以下の効果を適用する。\n蓄積された駒の重さが「10以上」となった滅をプレイマットに置いているプレイヤーは、手番プレイヤーの塔から１つ選び、その塔の駒を全て捨て駒にする。\n滅を含む蓄積された駒は全て捨て駒となり、滅の効果が終了する。', 'count' => 0),
    51 => array('number' => 51, 'name' => '封', 'type' => 'kotodama', 'weight' => 9, 'effect' => '自分以外のプレイヤーは、次の手番に「選」を行えない。', 'count' => 0),
    52 => array('number' => 52, 'name' => '神', 'type' => 'kotodama', 'weight' => 9, 'effect' => '『一発奥義』\n神を「発」する手番中に、他の駒を「積･発」することは出来ない。\n自分と味方以外の塔から１つ選び、その塔の役名（桜花、蓮花、奇数蓮花、偶数蓮花）を予想して宣言する。\nその後、塔を公開する。\n［予想が的中した場合］\n→ 塔を奪い、自分の完成役とする。\nただし、獲得出来る点数は役の基礎点のみ。\n［予想が外れた場合］\n→ 塔の所有者は「塔の解放」の処理を適用する。 ', 'count' => 0),
    53 => array('number' => 53, 'name' => '烙', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    54 => array('number' => 54, 'name' => '隷', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    55 => array('number' => 55, 'name' => '呪', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    56 => array('number' => 56, 'name' => '逆', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    57 => array('number' => 57, 'name' => '狼', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    58 => array('number' => 58, 'name' => '魂', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    59 => array('number' => 59, 'name' => '丹', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    60 => array('number' => 60, 'name' => '狂', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    61 => array('number' => 61, 'name' => '蘇', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    62 => array('number' => 62, 'name' => '碧', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    63 => array('number' => 63, 'name' => '眼', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    64 => array('number' => 64, 'name' => '妖', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    65 => array('number' => 65, 'name' => '魔', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    66 => array('number' => 66, 'name' => '氷', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    67 => array('number' => 67, 'name' => '牙', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    68 => array('number' => 68, 'name' => '歪', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    69 => array('number' => 69, 'name' => '砕', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    70 => array('number' => 70, 'name' => '鏡', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    71 => array('number' => 71, 'name' => '鎧', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    72 => array('number' => 72, 'name' => '亀', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    73 => array('number' => 73, 'name' => '隆', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    74 => array('number' => 74, 'name' => '玉', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    75 => array('number' => 75, 'name' => '雲', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    76 => array('number' => 76, 'name' => '雅', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    77 => array('number' => 77, 'name' => '煉', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    78 => array('number' => 78, 'name' => '廻', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    79 => array('number' => 79, 'name' => '輝', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    80 => array('number' => 80, 'name' => '刻', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    81 => array('number' => 81, 'name' => '仙', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    82 => array('number' => 82, 'name' => '朧', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    83 => array('number' => 83, 'name' => '雪', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    84 => array('number' => 84, 'name' => '晶', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    85 => array('number' => 85, 'name' => '煌', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    86 => array('number' => 86, 'name' => '冥', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    87 => array('number' => 87, 'name' => '極', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    88 => array('number' => 88, 'name' => '淵', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    89 => array('number' => 89, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    90 => array('number' => 90, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    91 => array('number' => 91, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    92 => array('number' => 92, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    93 => array('number' => 93, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    94 => array('number' => 94, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    95 => array('number' => 95, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    96 => array('number' => 96, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    97 => array('number' => 97, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    98 => array('number' => 98, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    99 => array('number' => 99, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    100 => array('number' => 100, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    101 => array('number' => 101, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    102 => array('number' => 102, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    103 => array('number' => 103, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    104 => array('number' => 104, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    105 => array('number' => 105, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    106 => array('number' => 106, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    107 => array('number' => 107, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
    108 => array('number' => 108, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'effect' => '', 'count' => 0),
);

// game_setup を動的に生成
// piece_types の count をループして、deckA と deckB に交互に配置
$this->game_setup = array(
    'deckA' => array(),
    'deckB' => array(),
    'kyoukokuA' => array(),
    'kyoukokuB' => array(),
    'insideA1' => array(),
    'insideA2' => array(),
    'insideA3' => array(),
    'insideB1' => array(),
    'insideB2' => array(),
    'insideB3' => array(),
    'handA1' => array(),
    'handA2' => array(),
    'handB1' => array(),
    'handB2' => array(),
    'moonA' => array(),
    'moonB' => array(),
    'oumoncircleA1' => array(),
    'oumoncircleA2' => array(),
    'oumoncircleA3' => array(),
    'oumoncircleB1' => array(),
    'oumoncircleB2' => array(),
    'oumoncircleB3' => array(),
    'towerA1' => array(),
    'towerA2' => array(),
    'towerA3' => array(),
    'towerA4' => array(),
    'towerA5' => array(),
    'towerA6' => array(),
    'towerA7' => array(),
    'towerB1' => array(),
    'towerB2' => array(),
    'towerB3' => array(),
    'towerB4' => array(),
    'towerB5' => array(),
    'towerB6' => array(),
    'towerB7' => array(),
    'exclusion' => array(),
);

// kyoukokuA, B に24個ずつランダムな駒を追加
for ($position = 0; $position < 24; $position++) {
    $randomType = rand(1, 88);
    $this->game_setup['kyoukokuA'][] = array('type' => $randomType, 'face' => 'front');
    $randomType = rand(1, 88);
    $this->game_setup['kyoukokuB'][] = array('type' => $randomType, 'face' => 'front');
}

// その他のコンテナに各1個ずつランダムな駒を追加
$singleContainers = array(
    'insideA1', 'insideA2', 'insideA3', 'insideB1', 'insideB2', 'insideB3',
    'handA1', 'handA2', 'handB1', 'handB2',
    'moonA', 'moonB',
    'oumoncircleA1', 'oumoncircleA2', 'oumoncircleA3', 'oumoncircleB1', 'oumoncircleB2', 'oumoncircleB3',
    'towerA1', 'towerA2', 'towerA3', 'towerA4', 'towerA5', 'towerA6', 'towerA7',
    'towerB1', 'towerB2', 'towerB3', 'towerB4', 'towerB5', 'towerB6', 'towerB7',
    'exclusion'
);

foreach ($singleContainers as $containerId) {
    $randomType = rand(1, 88);
    $this->game_setup[$containerId][] = array('type' => $randomType, 'face' => 'front');
}

$deckACount = 0;
$deckBCount = 0;
$useA = true;  // deckA と deckB を交互に使用

for ($typeNum = 1; $typeNum <= 88; $typeNum++) {
    if (!isset($this->piece_types[$typeNum])) {
        continue;
    }
    
    $piece = $this->piece_types[$typeNum];
    $count = isset($piece['count']) ? $piece['count'] : 0;
    
    // count 個分の駒を交互に deckA/B に追加
    for ($i = 0; $i < $count; $i++) {
        if ($useA) {
            $this->game_setup['deckA'][] = array('type' => $typeNum, 'face' => 'front');
            $deckACount++;
        } else {
            $this->game_setup['deckB'][] = array('type' => $typeNum, 'face' => 'front');
            $deckBCount++;
        }
        $useA = !$useA;  // 交互に切り替え
    }
}













