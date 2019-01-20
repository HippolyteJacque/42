using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.AI;

public class ZombieMove : MonoBehaviour {

	[System.NonSerialized]public float HP;
	[System.NonSerialized]public float maxHP;
	private float STR;
	[System.NonSerialized]public float AGI;
	private float CON;
	[System.NonSerialized]public float Armor;
	private float minDamage;
	private float maxDamage;
	[System.NonSerialized]public float XP;
	[System.NonSerialized]public int Level;
	[System.NonSerialized]public float money;
	private float chaseDistance = 15f;
	private float attackDistance = 2f;
	private float fireRate = 0.75f;
	private float nextFire;
	public bool alive;

	private GameObject Maya;

    public AudioClip attacking;
    public AudioClip dying;
    public AudioSource audioSource;

	Animator animator;

	NavMeshAgent agent;
        
    void Start() {
    	Maya = GameObject.FindWithTag("Player");
    	Level = Maya.GetComponent<MayaMove>().Level;
    	alive = true;

    	STR = 4;
    	AGI = 5;
    	CON = 9;
    	XP = 70;
    	for (int i = 1; i < Level; i++)
		{
			STR = STR*1.15f;
			AGI = AGI*1.15f;
			CON = CON*1.15f;
			XP = XP*1.15f;
		}
    	Armor = 5;
    	minDamage = STR/2;
    	maxDamage = minDamage + 4;
		HP = 5 * CON;
		maxHP = HP;
		money = 50;

        agent = GetComponent<NavMeshAgent>();
        animator = GetComponent<Animator>();
    }
    
    void Update() {

    	if (HP > 0 && alive == true){
    		if (Vector3.Distance(Maya.transform.position, transform.position) <= chaseDistance && Vector3.Distance(Maya.transform.position, transform.position) > attackDistance){
    			transform.LookAt(Maya.transform.position);
    			agent.enabled = true;
	    		agent.destination = Maya.transform.position;
	    		animator.SetBool("isWalking", true);
	    		animator.SetBool("isFighting", false);
	    	}
	    	else if (Vector3.Distance(Maya.transform.position, transform.position) < attackDistance){
	    		transform.LookAt(Maya.transform.position);
	    		animator.SetBool("isWalking", false);
	    		animator.SetBool("isFighting", true);
	    		attack(Maya);
	    	}
	    	else {
	    		animator.SetBool("isFighting", false);
	    		animator.SetBool("isWalking", false);
	    	}
    	}

    	if (HP <= 0 && alive == true){
    		alive = false;
            die();
    		animator.SetBool("isWalking", false);
    		animator.SetBool("isFighting", false);
    		animator.SetBool("alive", false);
    		StartCoroutine("death");
    	}
    }

    void attack(GameObject enemy){
    	agent.enabled = false;
    	if (Time.time > nextFire && enemy.GetComponent<MayaMove>().HP > 0){
    		StopCoroutine("dmg");
			animator.SetBool("isFighting", true);
			nextFire = Time.time + fireRate;
			StartCoroutine(dmg(enemy));
		}
    	if (enemy.GetComponent<MayaMove>().HP <= 0){
    		animator.SetBool("isFighting", false);
    		agent.enabled = true;
    	}
    }

    public void TakeDmg(float dmg){
        HP = HP - dmg;

    }

    IEnumerator dmg(GameObject enemy){
    	yield return new WaitForSeconds(0.3f);
    	float hitChance = 75 + AGI - enemy.GetComponent<MayaMove>().AGI;
    	if (Random.Range(0f, 100f) <= hitChance){
            audioSource.PlayOneShot(attacking);
    		float dmg = Random.Range(minDamage, maxDamage);
    		dmg = dmg * (1 - enemy.GetComponent<MayaMove>().Armor/200);
    		enemy.GetComponent<MayaMove>().HP = enemy.GetComponent<MayaMove>().HP - dmg;
    	}
    }

    public void die(){
        MayaMove MayaS = Maya.GetComponent<MayaMove>();
        MayaS.money += money;
        if (MayaS.XP + XP >= MayaS.xpNextLvl){
            MayaS.LevelUp(XP);
        }
        else {
            MayaS.XP += XP;
        }
    }

    IEnumerator death(){
    	if (Random.Range(0f, 100f) <= 100f){
			if (Random.Range(0, 100f) < 0f)
	    		Instantiate(SpritePool.instance.lifeBall, transform.position, Quaternion.identity);
			else
			{
    			Instantiate(SpritePool.instance.itemInGame, transform.position, Quaternion.Euler(Vector3.right * 90f)).GetComponent<ItemStats>().InitStat();

			}

    	}
    	Destroy(GetComponent<CapsuleCollider>());
    	Destroy(agent);
        audioSource.PlayOneShot(dying);

		yield return new WaitForSeconds(3);
		float t =0;
		while (true)
		{
	    	transform.Translate(-Vector3.up * Time.deltaTime/5);
			t += Time.deltaTime;
			if (t > 2f);
			yield return null;
		}
    	yield return new WaitForSeconds(1);
    	Destroy(gameObject);
    }

}
