<?php

/**
 * 駒の種類定義（1〜108）
 * number: 駒番号（1-108）
 * name: 駒の名前
 * code: 効果処理用コード
 * type: 駒の種類
 * weight: 駒の重量
 * count: 同一駒の総数
 * available: 使用可能フラグ
 * effect: 駒の効果説明（UIで表示）
 */
$this->piece_types = array(
    1 => array('number' => 1, 'name' => '一', 'type' => 'otonashi', 'weight' => 1, 'count' => 4, 'available' => true, 'effect' => ''),
    2 => array('number' => 2, 'name' => '二', 'type' => 'otonashi', 'weight' => 2, 'count' => 4, 'available' => true, 'effect' => ''),
    3 => array('number' => 3, 'name' => '三', 'type' => 'otonashi', 'weight' => 3, 'count' => 4, 'available' => true, 'effect' => ''),
    4 => array('number' => 4, 'name' => '四', 'type' => 'otonashi', 'weight' => 4, 'count' => 4, 'available' => true, 'effect' => ''),
    5 => array('number' => 5, 'name' => '五', 'type' => 'otonashi', 'weight' => 5, 'count' => 4, 'available' => true, 'effect' => ''),
    6 => array('number' => 6, 'name' => '六', 'type' => 'otonashi', 'weight' => 6, 'count' => 4, 'available' => true, 'effect' => ''),
    7 => array('number' => 7, 'name' => '七', 'type' => 'otonashi', 'weight' => 7, 'count' => 4, 'available' => true, 'effect' => ''),
    8 => array('number' => 8, 'name' => '八', 'type' => 'otonashi', 'weight' => 8, 'count' => 0, 'available' => true, 'effect' => ''),
    9 => array('number' => 9, 'name' => '雷', 'type' => 'kotodama', 'weight' => 1, 'count' => 8, 'available' => true, 'effect' => ''),
    10 => array('number' => 10, 'name' => '蛇', 'type' => 'kotodama', 'weight' => 2, 'count' => 7, 'available' => true, 'effect' => ''),
    11 => array('number' => 11, 'name' => '斬', 'type' => 'kotodama', 'weight' => 3, 'count' => 6, 'available' => true, 'effect' => ''),
    12 => array('number' => 12, 'name' => '陣', 'type' => 'kotodama', 'weight' => 4, 'count' => 5, 'available' => true, 'effect' => ''),
    13 => array('number' => 13, 'name' => '轟', 'type' => 'kotodama', 'weight' => 5, 'count' => 4, 'available' => true, 'effect' => ''),
    14 => array('number' => 14, 'name' => '霧', 'type' => 'kotodama', 'weight' => 6, 'count' => 3, 'available' => true, 'effect' => ''),
    15 => array('number' => 15, 'name' => '瞬', 'type' => 'kotodama', 'weight' => 7, 'count' => 2, 'available' => true, 'effect' => ''),
    16 => array('number' => 16, 'name' => '浄', 'type' => 'kotodama', 'weight' => 8, 'count' => 1, 'available' => true, 'effect' => ''),
    17 => array('number' => 17, 'name' => '覇', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => ''),
    18 => array('number' => 18, 'name' => '天', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '自分または味方の塔を１つ選ぶ。\nその塔の１番上の駒と、捨て駒もしくは全プレイヤーの内駒から１つ駒を選び、選んだ駒の場所を入れ替える。'),
    19 => array('number' => 19, 'name' => '啓', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '２段以上の塔を保持していて、空いている桜門があるプレイヤーから１人選ぶ。\nそのプレイヤーの塔を１つ選び、好きな部分から上下２つに分け、片方をそのまま空いている桜門へ置く。'),
    20 => array('number' => 20, 'name' => '和', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '自分の３段以上の塔を１つ選び、その塔の駒を全て公開する。\n公開した駒の重さの合計が…\n・１１の場合 → ２点獲得\n・３３の場合 → ４点獲得\n公開した駒を完成役と同様にプレイマット横に並べて置く。\n・１１、３３以外の場合→１点失う\n公開した駒を捨て駒として峡谷に置く。'),
    21 => array('number' => 21, 'name' => '禅', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '全プレイヤーは、体内の最も重い駒を１つ選び、その駒を右隣のプレイヤーの体内へ移動させる。\nその後、使用者の判断で、もう１度だけこの効果を適用させることも出来る。'),
    22 => array('number' => 22, 'name' => '楼', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『一発奥義』\n楼を「発」する手番中に、他の駒を「積･発」することは出来ない。\n自分と味方の塔の中から１つ選び、その塔の駒を好きな順番で積み直す。'),
    23 => array('number' => 23, 'name' => '龍', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『口寄せ奥義』\n自分の３段以上の塔を、１つ崩して捨て駒にすることで、その空いた桜門の上に、龍を表向きに「積」する。\n龍が、桜門の上に表向きで見えている間、龍の効果を毎手番「発」することが可能となる。\n※龍は「発」した後も桜門に留まる。\n《龍の効果》\n自分と味方以外の内駒から１つ選び、その駒を奪う。奪った駒は、自分の空いている手へ移動する。\n※手が空いていない時は「発」出来ない。'),
    24 => array('number' => 24, 'name' => '琥', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『口寄せ奥義』\n自分の３段以上の塔を、１つ崩して捨て駒にすることで、その空いた桜門の上に、琥を表向きに「積」する。\n琥が、桜門の上に表向きで見えている間、琥の効果を毎手番「発」することが可能となる。\n※琥は「発」した後も桜門に留まる。\n《琥の効果》\n全ての塔の中から１つ選び、その塔の１番上の駒を捨て駒にする。'),
    25 => array('number' => 25, 'name' => '舞', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『持続奥義』\n舞をプレイマットの頭に表向きで置く。\nその後、自分を対象とする「雷」「蛇」「斬」の効果を必ず防ぐ。\n防いだ言霊は捨て駒にならず、舞の横に並べて置き蓄積される。\n蓄積された駒の重さが「３以上」になった時点で、舞を含む蓄積された駒は全て捨て駒となり、舞の効果が終了する。\n※舞の効果で防ぐ前に「陣」や「轟」の効果を適用することは可能。\n※雷轟合戦中に舞の効果は適用されない。'),
    26 => array('number' => 26, 'name' => '忍', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『受動奥義』\n忍は「発」することが出来ない。\n「完成役」または「捨て駒」となることで「忍」が表向きになったとき、即座に以下の効果を２回適用する。\n全プレイヤーの「体内・手・峡谷」にある駒の中から２駒を選び、選んだ駒の場所を入れ替える。\nただし、峡谷にある駒を選ぶ場合、重さ８以上の言霊は選べない。\n効果の対象は、忍を最後に所持していたプレイヤーが指定する。'),
    27 => array('number' => 27, 'name' => '零', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '全プレイヤーは、体内の駒を全て雷山へ表向きに戻す。\n戻した駒は全て雷山として扱う。\n※捨て駒と混ざらないように注意する。\n※雷山がない場合は「発」出来ない。'),
    28 => array('number' => 28, 'name' => '怒', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '全プレイヤーは、自身の峡谷に置かれた「斬」の数だけ、自身の塔に斬の効果を適用する。\n効果の対象は各々自分が決める。\n※塔が無い、または無くなった場合、残りの斬の効果は適用しない。 \n※この効果に陣の効果を使うことは出来ない。'),
    29 => array('number' => 29, 'name' => '崩', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『受動奥義』\n崩は「発」することが出来ない。\n崩が、別の言霊の効果によって「体内または手」へ移動したとき、即座に以下の効果を適用する。\n崩の移動先の内駒は、崩を含め全て捨て駒となる。\n※浄の効果後『崩』を所持しているならば、崩の効果は適用される。'),
    30 => array('number' => 30, 'name' => '月', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '使用者の内駒から１つ捨て駒にする。他のプレイヤーは、使用者の捨てた駒と同じ駒が「体内または手」にあれば、その駒を全て捨て駒にする。'),
    31 => array('number' => 31, 'name' => '嵐', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '全プレイヤーの「体内または手」にある「雷」を全て回収する。\n回収した「雷」の数と同じ回数、以下の効果を適用する。\n最も高い塔の中から１つ選び、「塔の解放」の処理を行う。\nこの効果は、２段以下の塔にも適用される。\nその後、回収した「雷」を全て捨て駒にする。\n※ 「陣」と「轟」の効果は適用出来ない。'),
    32 => array('number' => 32, 'name' => '蒼', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '持続奥義』\n蒼をプレイマットの頭に表向きで置く。\nその後全プレイヤーは、塔を６段積み上げたとしても「塔の解放」は適用されず、積み上げた６段目の駒は、蒼の横に並べて置き蓄積される。\n蓄積された駒の重さが「６以上」になった時点で、蒼を含む蓄積された駒は全て捨て駒となり、蒼の効果が終了する。\n※複数の蒼が起動している場合は、６段目を「積」したプレイヤーが蓄積される蒼を選ぶ。'),
    33 => array('number' => 33, 'name' => '美', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『受動奥義』\n美は「発」することが出来ない。\n美が「体内または手」に置いてあり、他のプレイヤーが「蛇の効果」を使用したとき、即座に以下の効果を適用する。\n蛇の効果対象は、必ず「美」になる。\n※美の効果を適用する前に「陣」の効果を適用することは出来ない。'),
    34 => array('number' => 34, 'name' => '麗', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『受動奥義』\n麗は「発」することが出来ない。\n全プレイヤーの最終手番が終了した時点で、麗が自身の「体内または手」に置いてあり、その他の内駒が全て「音無」だった場合、即座に以下の効果を適用する。\n内駒の音無１つにつき、陽玉を１つ獲得する。'),
    35 => array('number' => 35, 'name' => '幻', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '捨て駒から１つ選び、豪雷と入れ替える。\n選んだ駒が豪雷となる。\n※２人戦の場合\nセットアップ時に除外した駒を、自分の体内もしくは手の空いている場所に配置する。'),
    36 => array('number' => 36, 'name' => '鳴', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '「雷、蛇、斬、陣、轟、霧、瞬、浄」のどれか１つと同じ効果を適用する。'),
    37 => array('number' => 37, 'name' => '赫', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '全プレイヤーは、次の手番のみ強制的に「現」が０個となる。'),
    38 => array('number' => 38, 'name' => '潤', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '自分の体内から駒を好きな数選び「峡谷」へ捨てる。\n捨てた駒と同じ数「峡谷」から駒を選び、体内に配置する。\nただし峡谷から「複数の同じ駒」と「奥義駒」は選べない。\n※体内に駒がない場合は「発」できない。\n※峡谷の駒数よりも多い駒を体内から捨てることはできない。'),
    39 => array('number' => 39, 'name' => '樹', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『口寄せ奥義』\n自分の３段以上の塔を、１つ崩して捨て駒にすることで、その空いた桜門の上に、樹を表向きに「積」する。\nこの塔を「樹の塔」と呼ぶ。\n「樹の塔」は以下の効果を適用する。\n《樹の塔の効果》\n樹の塔への言霊の効果を無効化する。また効果の対象として選ぶことも出来ない。\nこの塔へ積む駒は全て表向きでなければならない。\n※この塔が最も高い場合、２番目に高い塔が「雷」の効果対象となる。'),
    40 => array('number' => 40, 'name' => '麟', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '自分以外の２段以上の塔から１つ選ぶ。\n選んだ塔の中の好きな駒を１つ選び、その駒を麟があった手に移動させる。'),
    41 => array('number' => 41, 'name' => '鬼', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『持続奥義』\n鬼をプレイマットの頭に表向きで置く。\nその後、自分と味方以外のプレイヤーが重さ８以下の言霊の効果を適用して処理を終えたとき、自分の体内に空きがある場合に以下の効果を適用する。\n体内が全て埋まるまで雷山から駒を引く。\n鬼は捨て駒となり効果が終了する。\n※複数の鬼が起動していた場合、重さ８以下の言霊を使用したプレイヤーが効果適用者の順番を決める。\n※雷山が無くなった場合、そこで効果は終了となる。'),
    42 => array('number' => 42, 'name' => '縛', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '全プレイヤーの内駒の中から１つ選び、その駒に「縛」を表向きで重ねて置く。\n「縛を置かれた駒」は「積・発・駒の移動」を行うことが出来ない。\nまた、「縛を置かれた駒」は全ての言霊の効果を受けず、効果の対象として選ぶことも出来ない。\n「縛」を置かれたプレイヤーの、次の手番が終わると「縛」は捨て駒となり、効果が終了する。\n※縛と縛を置かれた駒は１つの駒として扱い、重さ順による体内の置き直しも行えない。'),
    43 => array('number' => 43, 'name' => '影', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '「雷、蛇、斬、陣、轟、霧、瞬、浄」のいずれかの言霊と同じ効果を適用することが出来る。\nただし適用するには、まず適用したい言霊と同じ重さの音無を、自分の内駒から捨て駒にしなければならない。\n※自分の内駒に音無が無い場合は「発」出来ない。'),
    44 => array('number' => 44, 'name' => '鳳', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => 'プレイヤーを１人選び、そのプレイヤーの内駒と、自分の内駒を全て入れ替える。\nその後、両プレイヤーは入れ替えた内駒を「体内または手」の好きな場所に置き直す。\n※自分または相手の内駒が無い場合も発することが出来る。'),
    45 => array('number' => 45, 'name' => '焔', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『一発奥義』\n焔を「発」する手番中に、他の駒を「積･発」することは出来ない。\n塔がある「桜門」を１つ選び、その桜門の前に「焔」を置くことで桜門を炎上状態にする。\n炎上状態の桜門に置かれている塔は「隠駒」が動くたびに、１番下の駒が捨て駒となる。\n塔が無くなると「焔」は捨て駒となり効果が終了する。\n※「雷停中の塔」がある「桜門」は、焔の対象にすることが出来ない。\n※炎上中の桜門にある塔を雷停した場合、雷停の効果が終わるまで焔の効果も停止する。'),
    46 => array('number' => 46, 'name' => '姫', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '他プレイヤーの、体内の駒から１つ選ぶ。\n選んだ駒を「姫」があった手へ移動する。\nその後、選んだ駒が元あった場所へ「姫」を移動する。'),
    47 => array('number' => 47, 'name' => '涼', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '全プレイヤーは内駒を全て表向きにする。\n表になった駒は「積」するまで表のまま。'),
    48 => array('number' => 48, 'name' => '風', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '雷山から《プレイ人数＋１》駒を引く。その中から１つ選び自分の「手」に置く。\n残った駒は表向きで雷山に戻す。\n戻した駒は全て雷山として扱う。\n※捨て駒と混ざらないように注意する。\n※雷山が《プレイ人数＋１》駒以上ない場合「発」出来ない'),
    49 => array('number' => 49, 'name' => '凛', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '自分または味方の塔の中から、役が完成している塔を１つ選び公開する。\nその後、雷山から駒を１つ引く。\n引いた駒が…\n［音無の場合］\n→ 役の点数＋２点 を獲得する。\n［言霊の場合］\n→ 役の点数が０点になり、全て捨て駒になる。\n雷山から引いた駒は捨て駒にする。\n※雷山がない場合は「発」出来ない'),
    50 => array('number' => 50, 'name' => '滅', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『持続奥義』\n滅をプレイマットの頭に表向きで置く。\nその後、効果を適用した言霊は捨て駒にならず、滅の横に並べて置き蓄積される。\nただし奥義駒は蓄積されない。\n蓄積された駒の重さが「10以上」になった手番の最後に以下の効果を適用する。\n蓄積された駒の重さが「10以上」となった滅をプレイマットに置いているプレイヤーは、手番プレイヤーের塔から１つ選び、その塔の駒を全て捨て駒にする。\n滅を含む蓄積された駒は全て捨て駒となり、滅の効果が終了する。'),
    51 => array('number' => 51, 'name' => '封', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '自分以外のプレイヤーは、次の手番に「選」を行えない。'),
    52 => array('number' => 52, 'name' => '神', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => true, 'effect' => '『一発奥義』\n神を「発」する手番中に、他の駒を「積･発」することは出来ない。\n自分と味方以外の塔から１つ選び、その塔の役名（桜花、蓮花、奇数蓮花、偶数蓮花）を予想して宣言する。\nその後、塔を公開する。\n［予想が的中した場合］\n→ 塔を奪い、自分の完成役とする。\nただし、獲得出来る点数は役の基礎点のみ。\n［予想が外れた場合］\n→ 塔の所有者は「塔の解放」の処理を適用する。 '),
    53 => array('number' => 53, 'name' => '烙', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    54 => array('number' => 54, 'name' => '隷', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    55 => array('number' => 55, 'name' => '呪', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    56 => array('number' => 56, 'name' => '逆', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    57 => array('number' => 57, 'name' => '狼', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    58 => array('number' => 58, 'name' => '魂', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    59 => array('number' => 59, 'name' => '丹', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    60 => array('number' => 60, 'name' => '狂', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    61 => array('number' => 61, 'name' => '蘇', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    62 => array('number' => 62, 'name' => '碧', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    63 => array('number' => 63, 'name' => '眼', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    64 => array('number' => 64, 'name' => '妖', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    65 => array('number' => 65, 'name' => '魔', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    66 => array('number' => 66, 'name' => '氷', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    67 => array('number' => 67, 'name' => '牙', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    68 => array('number' => 68, 'name' => '歪', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    69 => array('number' => 69, 'name' => '砕', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    70 => array('number' => 70, 'name' => '鏡', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    71 => array('number' => 71, 'name' => '鎧', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    72 => array('number' => 72, 'name' => '亀', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    73 => array('number' => 73, 'name' => '隆', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    74 => array('number' => 74, 'name' => '玉', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    75 => array('number' => 75, 'name' => '雲', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    76 => array('number' => 76, 'name' => '雅', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    77 => array('number' => 77, 'name' => '煉', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    78 => array('number' => 78, 'name' => '廻', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    79 => array('number' => 79, 'name' => '輝', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    80 => array('number' => 80, 'name' => '刻', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    81 => array('number' => 81, 'name' => '仙', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    82 => array('number' => 82, 'name' => '朧', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    83 => array('number' => 83, 'name' => '雪', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    84 => array('number' => 84, 'name' => '晶', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    85 => array('number' => 85, 'name' => '煌', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    86 => array('number' => 86, 'name' => '冥', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    87 => array('number' => 87, 'name' => '極', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    88 => array('number' => 88, 'name' => '淵', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    89 => array('number' => 89, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    90 => array('number' => 90, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    91 => array('number' => 91, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    92 => array('number' => 92, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    93 => array('number' => 93, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    94 => array('number' => 94, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    95 => array('number' => 95, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    96 => array('number' => 96, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    97 => array('number' => 97, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    98 => array('number' => 98, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    99 => array('number' => 99, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    100 => array('number' => 100, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    101 => array('number' => 101, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    102 => array('number' => 102, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    103 => array('number' => 103, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    104 => array('number' => 104, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    105 => array('number' => 105, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    106 => array('number' => 106, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    107 => array('number' => 107, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
    108 => array('number' => 108, 'name' => '', 'type' => 'kotodama', 'weight' => 9, 'count' => 0, 'available' => false, 'effect' => ''),
);

// game_setup を動的に生成
// piece_types の count をループして、deckA と deckB に交互に配置
$this->game_setup = array(
    'deck' => array(),
    'kyoukoku_rival' => array(),
    'kyoukoku_myself' => array(),
    'inside_rival_1' => array(),
    'inside_rival_2' => array(),
    'inside_rival_3' => array(),
    'inside_myself_1' => array(),
    'inside_myself_2' => array(),
    'inside_myself_3' => array(),
    'hand_rival_1' => array(),
    'hand_rival_2' => array(),
    'hand_myself_1' => array(),
    'hand_myself_2' => array(),
    'moon_rival' => array(),
    'moon_myself' => array(),
    'oumoncircle_rival_1' => array(),
    'oumoncircle_rival_2' => array(),
    'oumoncircle_rival_3' => array(),
    'oumoncircle_myself_1' => array(),
    'oumoncircle_myself_2' => array(),
    'oumoncircle_myself_3' => array(),
    'tower_rival_1' => array(),
    'tower_rival_2' => array(),
    'tower_rival_3' => array(),
    'tower_rival_4' => array(),
    'tower_rival_5' => array(),
    'tower_rival_6' => array(),
    'tower_rival_7' => array(),
    'tower_myself_1' => array(),
    'tower_myself_2' => array(),
    'tower_myself_3' => array(),
    'tower_myself_4' => array(),
    'tower_myself_5' => array(),
    'tower_myself_6' => array(),
    'tower_myself_7' => array(),
    'exclusion' => array(),
);

/* テスト用の駒追加処理を無効化
// kyoukokuA, B に24個ずつランダムな駒を追加
for ($position = 0; $position < 24; $position++) {
    $randomType = rand(1, 88);
    $this->game_setup['kyoukoku_rival'][] = array('type' => $randomType, 'face' => 'front');
    $randomType = rand(1, 88);
    $this->game_setup['kyoukoku_myself'][] = array('type' => $randomType, 'face' => 'front');
}

// その他のコンテナに各1個ずつランダムな駒を追加
$singleContainers = array(
    'inside_rival_1', 'inside_rival_2', 'inside_rival_3', 'inside_myself_1', 'inside_myself_2', 'inside_myself_3',
    'hand_rival_1', 'hand_rival_2', 'hand_myself_1', 'hand_myself_2',
    'moon_rival', 'moon_myself',
    'oumoncircle_rival_1', 'oumoncircle_rival_2', 'oumoncircle_rival_3', 'oumoncircle_myself_1', 'oumoncircle_myself_2', 'oumoncircle_myself_3',
    'tower_rival_1', 'tower_rival_2', 'tower_rival_3', 'tower_rival_4', 'tower_rival_5', 'tower_rival_6', 'tower_rival_7',
    'tower_myself_1', 'tower_myself_2', 'tower_myself_3', 'tower_myself_4', 'tower_myself_5', 'tower_myself_6', 'tower_myself_7',
    'exclusion'
);

foreach ($singleContainers as $containerId) {
    if (isset($this->game_setup[$containerId])) {
        $randomType = rand(1, 88);
        $this->game_setup[$containerId][] = array('type' => $randomType, 'face' => 'front');
    }
}
*/

$deckACount = 0;
$deckBCount = 0;
$useA = true;  // deckA と deckB を交互に使用

$deck = array();

for ($typeNum = 1; $typeNum <= 88; $typeNum++) {
    if (!isset($this->piece_types[$typeNum])) {
        continue;
    }
    
    $piece = $this->piece_types[$typeNum];
    
    // available が false の駒はデッキに入れない
    if (isset($piece['available']) && $piece['available'] === false) {
        continue;
    }

    $count = isset($piece['count']) ? $piece['count'] : 0;
    
    // count 個分の駒を一時配列に追加
    for ($i = 0; $i < $count; $i++) {
        $deck[] = array('type' => $typeNum, 'face' => 'back');
    }
}

// デッキをシャッフル
shuffle($deck);

// 残りを山札に設定
$this->game_setup['deck'] = $deck;













