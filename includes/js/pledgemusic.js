jQuery(document).ready(function () {
        jQuery.fancybox({
            'width': '40%',
            'height': '40%',
            'autoScale': true,
            'transitionIn': 'fade',
            'transitionOut': 'fade',
			'overlayOpacity': pledgemusic_data.opacity,
			'title': pledgemusic_data.text,
			'scrolling': 'no',
			'content': '<div style="width:280px;"><a href="http://www.pledgemusic.com/projects/'+pledgemusic_data.pm_id+'"><img src="'+pledgemusic_data.badge_url+'"/></a></div>'
        });
});