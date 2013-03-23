<?
$title="Статистика";
$content="
<div class='news_body'><div class='news_text'><a onClick='show_loader();' href='http://metrika.yandex.ru/stat/dashboard/?counter_id=7927513'>Яндекс.Метрика</a></div></div>
<div class='news_body'><div class='news_title support'>Просмотры, визиты и посетители</div><div class='news_text'>
	<iframe class='hundred' style='height:360px' allowtransparency='true' id='traffic' src='http://metrika.yandex.ru/widget/traffic/summary/?chart_type=page_views%2Cvisits%2Cvisitors&amp;counter_id=7927513&amp;date1=20120105&amp;date2=20120205&amp;filter=month&amp;group=day' scrolling='no' frameborder='no'></iframe>
</div></div>

<div class='news_body'><div class='news_title support'>Источники, динамика переходов</div><div class='news_text'>
	<iframe class='hundred' style='height:360px' allowtransparency='true' id='source' src='http://metrika.yandex.ru/widget/sources/summary/?counter_id=7927513&amp;date1=20120105&amp;date2=20120205&amp;filter=month&amp;group=day' scrolling='no' frameborder='no'></iframe>
</div></div>

<div class='news_body'><div class='news_title support'>География, Популярные поисковые фразы</div><div class='news_text'>
<table class='hundred'><tr><td>
	<iframe class='hundred' style='height:360px' allowtransparency='true' id='map' src='http://metrika.yandex.ru/widget/geo/countries/?chart_type=map&amp;counter_id=7927513&amp;date1=20120105&amp;date2=20120205&amp;filter=month&amp;group=day' scrolling='no' frameborder='no'></iframe></td><td>
	<iframe class='hundred' style='height:360px' allowtransparency='true' id='search' src='http://metrika.yandex.ru/widget/sources/phrases/?counter_id=7927513&amp;date1=20120105&amp;date2=20120205&amp;filter=month&amp;group=day&amp;limit=12' scrolling='no' frameborder='no'></iframe>
	</td></tr></table>
</div></div>
";
?>