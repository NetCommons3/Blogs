NetCommonsApp.controller('Blogs.BlockSetting', function($scope) {

  /**
   * Use like button
   *
   * @return {void}
   */
  $scope.useLike = function() {
    var likeElement = $('#BlogSettingUseLike');
    var unlikeElement = $('#BlogSettingUseUnlike');
console.log(unlikeElement);
    if (likeElement[0].checked) {
      unlikeElement[0].disabled = false;
    } else {
      unlikeElement[0].disabled = true;
    }
  };
});
