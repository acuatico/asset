<?php

namespace App\Service;


use App\Models\User as UserModel;

class User
{
    use DatatableParameters;

    private $site;
    private $role;
    protected $baseUrl = 'user';

    /**
     * User constructor.
     * @param Site $site
     * @param Role $role
     */
    public function __construct(Site $site, Role $role)
    {
        $this->site = $site;
        $this->role = $role;
    }

    public function selectedSites($userId)
    {
        $user = UserModel::find($userId);
        return $user->sites->pluck('id');
    }

    public function selectedRoles($userId)
    {
        $user = UserModel::find($userId);
        return $user->roles->pluck('id');
    }

    public function site()
    {
        return $this->site;
    }

    public function role()
    {
        return $this->role;
    }

    public function store(array $inputs)
    {
        $user = UserModel::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => bcrypt($inputs['password'])
        ]);

        $user->assignSiteById($inputs['sites']);

        $user->assignRoleById($inputs['roles']);
    }

    public function update($id, array $inputs)
    {
        $user = UserModel::find($id);
        $user->name = $inputs['name'];
        $user->email = $inputs['email'];
        $user->save();

        $user->syncSitesById($inputs['sites']);

        $user->syncRolesById($inputs['roles']);
    }

    public function destroy($id)
    {
        $user = UserModel::find($id);
        $user->removeRoles();
        $user->removeSites();
        $user->delete();
    }

    public function getList()
    {
        return UserModel::all(['id', 'name', 'email']);
    }

    public function datatableData()
    {
        $users = $this->getList();
        $actions = $this->actionParameters(['edit','delete']);

        return (new DatatableGenerator($users))
            ->addActions($actions)
            ->addColumn('site', function($user) {
                return $this->getSites($user);
            })
            ->addColumn('role', function($user) {
                return $this->getRoles($user);
            })
            ->generate();
    }

    private function getSites($user)
    {
        $sites = $user->sites;
        return array_pluck($sites, 'name');
    }

    private function getRoles($user)
    {
        $roles = $user->roles;
        return array_pluck($roles, 'name');
    }

}