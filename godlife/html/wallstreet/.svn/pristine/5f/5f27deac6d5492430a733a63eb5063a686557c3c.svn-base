/*
   이미지 맵 제작 툴 js Class.
################ Sample #######################
<div id='map_box'><img src='http://hamt.co.kr/images/banner.jpg' /></div>
<div id='map_option_box'></div>

<script>
var map_obj = new HamtImageMapController('map_box', 'map_option_box');
</script>

<input type='button' value='소스제작' onclick="alert(map_obj.get_code('my_map_name'))" />
###############################################


제작일 : 2012.11.16. 
제작자 : 함승목.
 */


// 좌표 클래스
var Point = function(x, y) {
		this.x = x;
		this.y = y;
};
// 링크들이 각각 가져야 할 정보
var Link = function(el_div, option_el_div, point_lefttop, point_rightbottom, url, target, seq) {
		this.el = el_div;						// 사각영역 div
		this.option_el = option_el_div;			// 부가정보 옵션 row div

		this.lefttop = point_lefttop;			// 좌상단 x, y (point type)
		this.rightbottom = point_rightbottom;	// 우하단 x, y (point type)
		this.url = url;							// 영역 클릭시  URL (string)
		this.target = target;					// 새창 or 현재창 값 (string)
		this.focus_name = 'focus_'+seq;			// 영역 div내에 위치확인을 위해  <a name='#focus_1'></a> 형태의 a태그가 있다. 그 이름.
}

// 실제 사용 클래스.
var HamtImageMapController = function(box_id, option_box_id) {
		var obj = $('#'+box_id);				// 맵 대상 이미지를 감싸는 div
		obj.css({
						'padding' : '0px',
						'position': 'relative'
						});


		var option_obj = $('#'+option_box_id);	// 영역좌표 이외 추가정보 입력폼을 제공하는 폼들의 부모 div
		var points = [];						// 두개의 포인트(두번 클릭)가 한개의 영역이 됨. 첫번째 클릭 포인트 저장소.(변수로 할껄)
		var links = [];							// 영역정보 클래스(Link) 인스턴스 배열

		var el_count = 1;						// 영역 seq. Link.focus_name 에 사용됨.


		// 드래그 설정(resize, move)을 위한 참조값
		var down_x;			// 드래그 시작 x좌표
		var down_y;			// 드래그 시작 y좌표
		var click_mode = '';// 설정타입 (move|resize).
		var down_link_index;// 설정 영역들의 배열(links) 중 설정중인 영역이 저장된 배열 인덱스


		// 드래그시 블럭지정되지 않도록...
		$(document).mousemove(function(e){if(click_mode != '') e.preventDefault(); return false;});

		// image 클릭이벤트 핸들러 구현. (2번 클릭시 영역 생성)
		var bg_img = obj.children('img').get(0);
		var _this = this;
		$(bg_img).on('click', function(e) {
						if(click_mode != '') {
						return;
						}
						var offset = $(this).offset();
						_this.add_point(e.pageX-offset.left, e.pageY-offset.top);
						});


		// resize, move 모드 처리를 위한 이벤트 핸들러 구현.
		obj.on('mousemove', function(e){
						if(click_mode == '') {
						return;
						}
						var img = $(bg_img);
						var link = links[down_link_index];
						switch(click_mode) {
						case 'resize' : // left, top은 두고, width, height 속성값 변경
						var left = link.lefttop.x;
						var top = link.lefttop.y;
						var width = link.rightbottom.x - link.lefttop.x;
						var height = link.rightbottom.y - link.lefttop.y;

						if(width + (e.pageX - down_x) >= 10 && left + width + (e.pageX - down_x) <= img.width()) {
						link.el.style.width = (width + (e.pageX - down_x))+'px';
						}
						if(height + (e.pageY - down_y) >= 10 && top + height + (e.pageY - down_y) <= img.height()) {
						link.el.style.height= (height + (e.pageY - down_y))+'px';
						}
						break;
						case 'move' : // width, height값은 두고, left, top 속성값 변경
						var t_left = link.lefttop.x + (e.pageX - down_x);
						var t_top = link.lefttop.y + (e.pageY - down_y);
						var width = link.rightbottom.x - link.lefttop.x;
						var height = link.rightbottom.y - link.lefttop.y;

						if(t_left >= 0 && t_left+width <= img.width()) {
								link.el.style.left = t_left+'px';
						}
						if(t_top >= 0 && t_top+height <= img.height()) {
								link.el.style.top = t_top+'px';
						}
						break;
						}
		});

		// 설정 종료시 link클래스 오브젝트 속성값 재조정 및 링크 추가정보영역 좌표 업데이트
		obj.on('mouseup', function(e) {
						if(click_mode == '') {
						return;
						}
						switch(click_mode) {
						case 'resize' : 
						case 'move' : 
						var link = links[down_link_index];
						links[down_link_index].lefttop.x = parseInt(link.el.style.left.split('px')[0]);
						links[down_link_index].lefttop.y = parseInt(link.el.style.top.split('px')[0]);
						links[down_link_index].rightbottom.x = parseInt(link.el.style.left.split('px')[0]) + parseInt(link.el.style.width.split('px')[0]);
						links[down_link_index].rightbottom.y = parseInt(link.el.style.top.split('px')[0]) + parseInt(link.el.style.height.split('px')[0]);
						_this.set_option_el(link.option_el, links[down_link_index].lefttop.x, links[down_link_index].lefttop.y, parseInt(link.el.style.width.split('px')[0]), parseInt(link.el.style.height.split('px')[0]));
						break;
						}
						click_mode = '';
						});


		// 좌표 추가
		this.add_point = function(x, y) {
				if(points.length == 0) { // 시작점만 입력된 상황. 시작점 저장 후 리턴.
						points.push(new Point(x, y));
						return;
				}

				var p1 = points.pop();
				var p2 = new Point(x, y);


				// 좌상, 우하 포인트 구분하기
				var top = (p1.y < p2.y) ? p1.y : p2.y;
				var left = (p1.x < p2.x) ? p1.x : p2.x;
				var width = Math.abs(p1.x - p2.x);
				var height = Math.abs(p1.y - p2.y);

				// 빨간 사각박스 생성
				var div = document.createElement('div');
				obj.append(div);

				$(div).css({
								'position' : 'absolute',
								'top' : top+'px',
								'left' : left+'px',
								'width' : width+'px',
								'height' : height+'px',
								'border' : '1px solid red'
								});
				$(div).html('<a name="focus_'+el_count+'"></a>');

				//리사이즈 버튼
				var resize_div = document.createElement('div');
				$(div).append(resize_div);

				var resize_tooltip = document.createElement('div');
				resize_tooltip.className = 'map_tooltip';
				$(resize_div).append(resize_tooltip);
				$(resize_tooltip).css({
								'position' : 'absolute',
								'width' : '2.4em',
								'left' : '8px',
								'top' : '0px',
								'background' : '#fff',
								'border' : '1px solid black',
								'padding' : '1px',
								'display' : 'none',
								'fontSize' : '8pt'
								}).html('크기');
				$(resize_div).on('mouseover', function(e) {
								$('div.map_tooltip', this).css('display', '');
								});
				$(resize_div).on('mouseout', function(e) {
								$('div.map_tooltip', this).css('display', 'none');
								});

				$(resize_div).css({
								'position' : 'absolute',
								'right' : '0px',
								'bottom' : '0px',
								'width' : '5px',
								'height' : '5px',
								'background' : '#ef0000',
								'cursor' : 'pointer'
								});
				$(resize_div).on('mousedown', function(e) {
								if(click_mode != '') {
								return;
								}
								click_mode = 'resize';
								down_x = e.pageX;
								down_y = e.pageY;

								for(var i = 0 ; i < links.length ; i++) {
								if(links[i].el == this.parentNode) {
								down_link_index = i;
								break;
								}
								}
								});

				// 삭제버튼
				var del_div = document.createElement('div');
				$(div).append(del_div);

				var del_tooltip = document.createElement('div');
				del_tooltip.className = 'map_tooltip';
				$(del_div).append(del_tooltip);
				$(del_tooltip).css({
								'position' : 'absolute',
								'width' : '2.4em',
								'left' : '8px',
								'top' : '0px',
								'background' : '#fff',
								'border' : '1px solid black',
								'padding' : '1px',
								'display' : 'none',
								'fontSize' : '8pt'
								}).html('삭제');
				$(del_div).on('mouseover', function(e) {
								$('div.map_tooltip', this).css('display', '');
								});
				$(del_div).on('mouseout', function(e) {
								$('div.map_tooltip', this).css('display', 'none');
								});

				$(del_div).css({
								'position' : 'absolute',
								'right' : '0px',
								'top' : '0px',
								'width' : '5px',
								'height' : '5px',
								'background' : '#ef0000',
								'cursor' : 'pointer'
								});
				$(del_div).on('click', function(e) {
								if(click_mode != '') {
								return;
								}

								for(var i = 0 ; i < links.length ; i++) {
								if(links[i].el == this.parentNode) {
								_this.remove_link(links[i].el);
								break;
								}
								}
								});


				// 이동버튼
				var move_div = document.createElement('div');
				$(div).append(move_div);

				var move_tooltip = document.createElement('div');
				move_tooltip.className = 'map_tooltip';
				$(move_div).append(move_tooltip);
				$(move_tooltip).css({
								'position' : 'absolute',
								'width' : '2.4em',
								'left' : '8px',
								'top' : '0px',
								'background' : '#fff',
								'border' : '1px solid black',
								'padding' : '1px',
								'display' : 'none',
								'fontSize' : '8pt'
								}).html('이동');
				$(move_div).on('mouseover', function(e) {
								$('div.map_tooltip', this).css('display', '');
								});
				$(move_div).on('mouseout', function(e) {
								$('div.map_tooltip', this).css('display', 'none');
								});


				$(move_div).css({
								'position' : 'absolute',
								'left' : '0px',
								'top' : '0px',
								'width' : '5px',
								'height' : '5px',
								'background' : '#ef0000',
								'cursor' : 'pointer'
								});
				$(move_div).on('mousedown', function(e) {
								if(click_mode != '') {
								return;
								}
								click_mode = 'move';
								down_x = e.pageX;
								down_y = e.pageY;

								for(var i = 0 ; i < links.length ; i++) {
								if(links[i].el == this.parentNode) {
								down_link_index = i;
								break;
								}
								}
								});

				// 링크걸기 버튼
				var focus_div = document.createElement('div');
				$(div).append(focus_div);

				var focus_tooltip = document.createElement('div');
				focus_tooltip.className = 'map_tooltip';
				$(focus_div).append(focus_tooltip);
				$(focus_tooltip).css({
								'position' : 'absolute',
								'width' : '2.4em',
								'left' : '8px',
								'top' : '0px',
								'background' : '#fff',
								'border' : '1px solid black',
								'padding' : '1px',
								'display' : 'none',
								'fontSize' : '8pt'
								}).html('링크');
				$(focus_div).on('mouseover', function(e) {
								$('div.map_tooltip', this).css('display', '');
								});
				$(focus_div).on('mouseout', function(e) {
								$('div.map_tooltip', this).css('display', 'none');
								});


				$(focus_div).css({
								'position' : 'absolute',
								'left' : '0px',
								'bottom' : '0px',
								'width' : '5px',
								'height' : '5px',
								'background' : '#ef0000',
								'cursor' : 'pointer'
								});
				$(focus_div).on('click', function(e) {
								if(click_mode != '') {
								return;
								}

								for(var i = 0 ; i < links.length ; i++) {
								if(links[i].el == this.parentNode) {
								$('input', links[i].option_el).focus();
								break;
								}
								}
								});








				// 추가정보 노출 영역 생성
				var option_div = document.createElement('div');
				$(option_div).css({
								'margin' : '10px',
								'padding' : '7px',
								'border' : '1px solid #bbbbbb',
								'background' : '#fff'
								});
				option_obj.append(option_div);

				this.set_option_el(option_div, left, top, width, height); // 추가정보 채우기


				// 영역정보 클래스 인스턴스 배열 채우기
				links.push(new Link(div, option_div, new Point(left, top), new Point(left+width, top+height), '', '_self', el_count++));
		}


		// 추가정보 채우기
		this.set_option_el = function(option_div, left, top, width, height) {
				var str = '좌표 : <span class="pos" style="cursor:pointer;">'+left+', '+top+', '+(left+width)+', '+(top+height)+' [위치확인]</span> | ';
				str += "Link URL : <input  type='text' /> | ";
				str += "링크 열리는곳 : <select><option value='_self'>현재창</option><option value='_blank'>새창</option></select> | ";
				str += "<span class='del' style='color:red;cursor:pointer'>[삭제]</span>";
				$(option_div).html(str);


				// resize / move로 인한 수정일 경우 기존값 유지
				if(click_mode != '') {
						$('input', option_div).get(0).value = links[down_link_index].url;
						$('select', option_div).get(0).value = links[down_link_index].target;
				}

				// url 설정시 저장
				$('input', option_div).on('change', function() {
								for(var i = 0 ; i < links.length ; i++) {
								if(links[i].option_el == this.parentNode) {
								links[i].url = this.value;
								break;
								}
								}
								});

				// target 설정시 저장(링크 열리는곳
				$('select', option_div).on('change', function() {
								for(var i = 0 ; i < links.length ; i++) {
								if(links[i].option_el == this.parentNode) {
								links[i].target= this.value;
								$(links[i].el).fadeOut(250).fadeIn(250).fadeOut(250).fadeIn(250);
								break;
								}
								}
								});


				// 좌표확인
				$('span.pos', option_div).on('click', function() {
								for(var i = 0 ; i < links.length ; i++) {
								if(links[i].option_el == this.parentNode) {
								location.href='#'+links[i].focus_name;
								$(links[i].el).fadeOut(250).fadeIn(250).fadeOut(250).fadeIn(250);
								break;
								}
								}
								});
				// 삭제
				$('span.del', option_div).on('click', function() {
								_this.remove_link(this.parentNode);
								});
		}

		// 해당 링크영역 삭제
		this.remove_link = function(rm_link_el) {
				if(confirm('정말 삭제해요?') == false) {
						return;
				}

				var res = [];
				var rm_link = null;
				for(var i = 0 ; i < links.length ; i++) {
						if(links[i].option_el == rm_link_el || links[i].el == rm_link_el) {
								rm_link = links[i];
								continue;
						}
						res.push(links[i]);
				}

				if(rm_link != null) {
						rm_link.el.parentNode.removeChild(rm_link.el);
						rm_link.option_el.parentNode.removeChild(rm_link.option_el);
						links = res;
				}
		}


		// 이미지맵 태그 뽑아내기
		this.get_code = function(map_name) {
				map_name = $.trim(map_name);
				if(map_name.length <= 0) {
						alert('이미지맵 Name을 설정하세요');
						return '';
				}
				//var html = '<img src="'+bg_img.src+'" usemap="#'+map_name+'" border="0">'+"\n";
				//html += '<!-- 위 이미지 태그의 src 속성은 서비스할 서버의 주소로 수정하여 사용하세요. -->'+"\n\n";
				var html = "<map name='"+map_name+"'>";

				for(var i = 0 ; i < links.length ; i++) {
						var link = links[i];
						html += "<area shape='rect' coords='"+link.lefttop.x+','+link.lefttop.y+','+link.rightbottom.x+','+link.rightbottom.y+"'";
						html += " href='"+link.url+"' target='"+link.target+"' alt='' title='' />";
				}
				html += '</map>';

				return html;
		}

}


