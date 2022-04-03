<?php

namespace dp\Excerpt;

use WP_User;

/**
 * Check if current user or specified user in a scope
 */
class UserScopeChecker {
	private $scope = array();

	public function __construct($scope) {
		$this->scope = $this->parseScope($scope);
	}

	public function parseScope($_scope) {
		// Initialize scope.
		$scope = array(
			'logged_in'     => false,
			'not_logged_in' => false,
			'role__in'      => array(),
			'role__not_in'  => array(),
			'cap__in'       => array(),
			'cap__not_in'   => array(),
			'cap_relation'  => '',
			'rc_relation'   => 'AND',
		);

		if (is_array($_scope)) {
			// Back compat.
			if (isset($_scope['caps'])) {
				$_scope['cap__in'] = $_scope['caps'];
			}
			if (isset($_scope['roles'])) {
				$_scope['role__in'] = $_scope['roles'];
			}

			$scope = array_merge($scope, array_intersect_key($_scope, $scope));
		} elseif ($_scope == 'all') {
			$scope['logged_in']     = true;
			$scope['not_logged_in'] = true;
		} elseif ($_scope == 'logged_in') {
			$scope['logged_in'] = true;
		} elseif ($_scope == 'not_logged_in') {
			$scope['not_logged_in'] = true;
		} elseif ($_scope == 'not_admins') {
			$scope['logged_in']     = true;
			$scope['not_logged_in'] = true;
			$scope['role__not_in']  = array('administrator');
		}

		return $scope;
	}

	public function inScope() {
		if (!is_user_logged_in()) {
			$in_scope = $this->scope['not_logged_in'];
		} else {
			$user     = wp_get_current_user();
			$in_scope = self::isScopedUser($user);
		}

		return $in_scope;
	}

	public function isScopedUser($user = 0) {
		$scope = $this->scope;

		if ($user && !($user instanceof WP_User)) {
			$user = get_user_by('ID', $user);
		}

		if (!$user) {
			return null;
		}

		$in_scope = $scope['logged_in'];

		if ($in_scope) {
			$in_role_scope = $this->inRoleScope($user);
			$in_cap_scope  = $this->inCapScope($user);

			if ($in_role_scope !== null && $in_cap_scope !== null && $scope['rc_relation'] == 'OR') {
				$in_scope = $in_role_scope || $in_cap_scope;
			} else {
				$in_scope = ($in_role_scope ?? true) && ($in_cap_scope ?? true);
			}
		}

		return $in_scope;
	}

	public function inRoleScope($user) {
		$in_scope = null;
		$scope    = $this->scope;

		$user_roles = (array) $user->roles;

		if (!empty($scope['role__in'])) {
			$in_scope = (bool) array_intersect($scope['role__in'], $user_roles);
		}

		if (!empty($scope['role__not_in'])) {
			$in_scope = !(bool) array_intersect($scope['role__not_in'], $user_roles);
		}

		return $in_scope;
	}

	public function inCapScope($user) {
		$in_scope = null;
		$scope    = $this->scope;

		if (!empty($scope['cap__in'])) {
			$in_scope = false;

			foreach ($scope['cap__in'] as $cap) {
				if ($user->has_cap($cap)) {
					$in_scope = true;
					break;
				}
			}
		}

		if (!empty($scope['cap__not_in'])) {
			$in_scope = true;

			foreach ($scope['cap__not_in'] as $cap) {
				if ($user->has_cap($cap)) {
					$in_scope = false;
					break;
				}
			}
		}

		return $in_scope;
	}
}
